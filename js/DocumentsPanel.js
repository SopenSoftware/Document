/*
 * Tine 2.0
 *
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: DocumentsPanel.js 14772 2010-06-03 08:52:34Z f.wiechmann@metaways.de $
 *
 * TODO         add to extdoc
 */

Ext.ns('Tine.Document');

/************************* panel *********************************/

/**
 * Class for a single documents panel
 *
 * @namespace   Tine.widgets.documents
 * @class       Tine.widgets.documents.DocumentsPanel
 * @extends     Ext.Panel
 */
//Tine.widgets.documents.DocumentsPanel = Ext.extend(Ext.Panel, {
Tine.Document.DocumentsPanel = Ext.extend(Ext.Panel, {
    /**
     * @cfg {String} app Application which uses this panel
     */
    app: '',

    /**
     * @cfg {Array} documents Initial documents
     */
    documents: [],

    /**
     * the translation object
     */
    translation: null,

    /**
     * @var {Ext.data.JsonStore}
     * Holds activities of the record this panel is displayed for
     */
    recordDocumentsStore: null,

    title: null,
    iconCls: 'notes_noteIcon',
    layout: 'hfit',
    bodyStyle: 'padding: 2px 2px 2px 2px',
    autoScroll: true,

    /**
     * init activities data view
     */
    initDocumentsDataView: function()
    {
        var DocumentsTpl = new Ext.XTemplate(
            '<tpl for=".">',
               '<div class="x-widget-activities-activitiesitem" id="{id}">',
                    '<div class="x-widget-activities-activitiesitem-text"',
                    '   ext:qtip="{[this.encode(values.note)]} - {[this.render(values.creation_time, "timefull")]} - {[this.render(values.created_by, "user")]}" >',
                        '{[this.render(values.note_type_id, "icon")]}&nbsp;{[this.render(values.creation_time, "timefull")]}<br/>',
                        '{[this.encode(values.note, true)]}<hr color="#aaaaaa">',
                    '</div>',
                '</div>',
            '</tpl>' ,{
                encode: function(value, ellipsis) {
                    var result = Ext.util.Format.nl2br(Ext.util.Format.htmlEncode(value));
                    return (ellipsis) ? Ext.util.Format.ellipsis(result, 300) : result;
                },
                render: function(value, type) {
                    switch (type) {
                        case 'icon':
                            return Tine.Document.getMimeTypeIcon(value);
                        case 'user':
                            if (!value) {
                                value = Tine.Tinebase.registry.map.currentAccount.accountDisplayName;
                            }
                            var username = value;
                            return '<i>' + username + '</i>';
                        case 'time':
                            if (!value) {
                                return '';
                            }
                            return value.format(Locale.getTranslationData('Date', 'medium'));
                        case 'timefull':
                            if (!value) {
                                return '';
                            }
                            return value.format(Locale.getTranslationData('Date', 'medium')) + ' ' +
                                value.format(Locale.getTranslationData('Time', 'medium'));
                    }
                }
            }
        );

        this.activities = new Ext.DataView({
            tpl: DocumentsTpl,
            id: 'grid_documents_limited',
            store: this.recordDocumentsStore,
            overClass: 'x-view-over',
            itemSelector: 'documents-item-small'
        });
    },

  /**
   * @private
   */
  initComponent: function(){
    // get translations
    this.translation = new Locale.Gettext();
    this.translation.textdomain('Tinebase');

    // translate / update title
    this.title = this.translation._('Dokumente');

    // init recordDocumentsStore
    this.documents = [];
    this.recordDocumentsStore = new Ext.data.JsonStore({
        id: 'id',
        fields: Tine.Document.Model.Document,
        data: this.documents,
        sortInfo: {
            field: 'creation_date',
            direction: 'DESC'
        }
    });

    Ext.StoreMgr.add('DocumentsStore', this.recordDocumentsStore);

    Tine.widgets.activities.DocumentsPanel.superclass.initComponent.call(this);
  }
});

/************************* tab panel *********************************/

/**
 * Class for a activities tab with documents/activities grid
 *
 * TODO add more filters to filter toolbar
 *
 *
 * @namespace   Tine.widgets.activities
 * @class       Tine.widgets.activities.DocumentsTabPanel
 * @extends     Ext.Panel
 */
//Tine.widgets.activities.DocumentsTabPanel = Ext.extend(Ext.Panel, {
Tine.Document.DocumentsTabPanel = Ext.extend(Ext.Panel, {

    /**
     * @cfg {String} app Application which uses this panel
     */
    app: '',

    /**
     * @var {Ext.data.JsonStore}
     * Holds activities of the record this panel is displayed for
     */
    store: null,

    /**
     * the translation object
     */
    translation: null,

    /**
     * @cfg {Object} paging defaults
     */
    paging: {
        start: 0,
        limit: 20,
        sort: 'creation_time',
        dir: 'DESC'
    },

    /**
     * the parent id
     */
    parent_id: null,

    /**
     * the record model
     */
    record_model: null,

    /**
     * The full record
     */
    record: null,

    /**
     * other config options
     */
	title: null,
	layout: 'fit',

    getDocumentsGrid: function()
    {
        // @todo add row expander on select ?
    	// @todo add context menu ?
    	// @todo add buttons ?
    	// @todo add more renderers ?

        var self = this;

        // the columnmodel
        var columnModel = new Ext.grid.ColumnModel([
            { resizable: true, id: 'mimetype', header: this.translation._('Preview'), dataIndex: 'mimetype', width: 15,
                renderer: Tine.Document.getPreviewImage },
            { resizable: true, id: 'name', header: this.translation._('Name'), dataIndex: 'name'},
            { resizable: true, id: 'version', header: this.translation._('Version'), dataIndex: 'version'},
            { resizable: true, id: 'comment', header: this.translation._('Comment'), dataIndex: 'comment', width: 270},
            { resizable: true, id: 'creation_date', header: this.translation._('Creation date'), dataIndex: 'creation_date', width: 50,
                _renderer: Tine.Tinebase.common.dateTimeRenderer },
            { resizable: true, id: 'download', header: this.translation._('Download'), dataIndex: 'id', renderer: Tine.Document.getDownloadLink},
        ]);

        columnModel.defaultSortable = true; // by default columns are sortable

        // the rowselection model
        var rowSelectionModel = new Ext.grid.RowSelectionModel({multiSelect:false});

        // the paging toolbar
        var pagingToolbar = new Ext.PagingToolbar({
            pageSize: 20,
            store: this.store,
            displayInfo: false,
            displayMsg: this.translation._('Displaying history records {0} - {1} of {2}'),
            emptyMsg: this.translation._("No history to display")
        });

        var folderName = self.record.data.name;

        if (self.record_model === 'Addressbook_Model_Contact') {
          folderName = self.record.data.n_fn;
        }

        if (self.record_model === 'Membership_Model_SoMember') {
          folderName = self.record.data.member_nr;
        }

        var addFolder = new Ext.Button({
          scale: 'small',
          text: 'Ordner anlegen',
          iconCls: 'icon-addFolder',
          disabled: false,
          handler: function() {
            Ext.Ajax.request({
              url: 'index.php',
              success: function(res) {
                addFolder.setDisabled(true);
                Ext.Msg.show({
                  title:'Ordner angelegt',
                  msg: 'Der Ordner "' + folderName + '" wurde erfolgreich angelegt.',
                  buttons: Ext.Msg.OK,
                  icon: Ext.MessageBox.INFO
                });
              },
              params: {
                parentId: self.parent_id,
                id: self.record.data.id,
                name: folderName,
                method: 'Document.createFolder'
              }
            });
          }
        });

        Ext.Ajax.request({
          url: 'index.php',
          success: function(response) {
            var obj = Ext.decode(response.responseText);

            if (obj.data.length > 0) {
              addFolder.setDisabled(true);
            }
          },
          params: {
            sopenid: self.record.data.id,
            method: 'Document.getFolderBySopenId'
          }
        });

        // the gridpanel
        var gridPanel = new Ext.grid.GridPanel({
            id: this.app + 'Documents_Grid',
            store: this.store,
            cm: columnModel,
            tbar: {
              items: [
                addFolder
              ]
            },
            selModel: rowSelectionModel,
            border: false,
            autoExpandColumn: 'comment',
            //enableColLock:false,
            //autoHeight: true,
            //loadMask: true,
            view: new Ext.grid.GridView({
                autoFill: true,
                forceFit:true,
                ignoreAdd: true,
                autoScroll: true
            })
        });

        gridPanel.on('afterrender', function() {
          var el = this.getEl();

          el.on('dragover', this.onDragOver, this);
          el.on('dragleave', this.onDragLeave, this);
          el.on('drop', this.onFileDrop, this);
        }, this);

        return gridPanel;
    },

    onDragOver: function(event, el) {
      event.stopPropagation();
      event.preventDefault();

      if (!el.classList.contains('x-grid3-scroller')) {
        return false;
      }

      el.style.backgroundColor = '#FFFFDB';
    },

    onDragLeave: function(event, el) {
      if (!el.classList.contains('x-grid3-scroller')) {
        return false;
      }

      el.style.backgroundColor = '#FFFFFF';
    },

    onFileDrop: function(event, el) {
      var self = this;
      el.style.backgroundColor = '#FFFFFF';
      
      // Prevent file to be opened in the browser window.
      event.preventDefault();

      this.activitiesGrid.getEl().mask('Dateien werden hochgeladen...', 'x-mask-loading');

      for (var i = 0; i < event.browserEvent.dataTransfer.files.length; ++i) {
        var f = event.browserEvent.dataTransfer.files[i];
        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = function(e) {
          Ext.Ajax.request({
            url: 'index.php',
            callback: function(response) {
              self.activitiesGrid.getEl().unmask();
            },
            params: {
              parentid: self.record.id,
              blob: e.target.result,
              name: f.name,
              method: 'Document.getUpload'
            }
          });
        };

        // Read in the image file as a data URL.
        reader.readAsBinaryString(f);
      };

      return false;
    },

    /**
     * init the contacts json grid store
     */
    initStore: function(){

        this.store = new Ext.data.JsonStore({
            id: 'id',
            autoLoad: false,
            root: 'results',
            totalProperty: 'totalcount',
            fields: Tine.Document.Model.Document,
            remoteSort: true,
            baseParams: {
                method: 'Document.searchDocuments'
            },
            sortInfo: {
                field: this.paging.sort,
                direction: this.paging.dir
            }
        });

        // register store
        Ext.StoreMgr.add(this.app + 'DocumentsGridStore', this.store);

        // prepare filter
        this.store.on('beforeload', function(store, options){
            if (!options.params) {
                options.params = {};
            }

            // paging toolbar only works with this properties in the options!
            options.params.sort  = store.getSortState() ? store.getSortState().field : this.paging.sort;
            options.params.dir   = store.getSortState() ? store.getSortState().direction : this.paging.dir;
            options.params.start = options.params.start ? options.params.start : this.paging.start;
            options.params.limit = options.params.limit ? options.params.limit : this.paging.limit;

            options.params.paging = Ext.copyTo({}, options.params, 'sort,dir,start,limit');

            var filterToolbar = Ext.getCmp('documentsFilterToolbar');
            var filter = filterToolbar ? filterToolbar.getValue() : [];
            filter.push(
                {field: 'record_model', operator: 'equals', value: this.record_model },
                {field: 'record_id', operator: 'equals', value: (this.record) ? this.record.id : 0 },
                {field: 'record_backend', operator: 'equals', value: 'Sql' }
            );

            options.params.filter = filter;

        }, this);

        // add new documents from documents store
        this.store.on('load', function(store, operation) {
        	documentsStore = Ext.StoreMgr.lookup('DocumentsStore');
        }, this);
    },

    /**
     * @private
     */
    initComponent: function() {
      if (!this.parent_id) {
        console.warn('Document: parent_id is missing!');
      }

      // get translations
      this.translation = new Locale.Gettext();
      this.translation.textdomain('Tinebase');

      // translate / update title
      this.title = this.translation._('Dokumente');

      // get store
      this.initStore();

      // get grid
      this.activitiesGrid = this.getDocumentsGrid();

      this.items = [
        new Ext.Panel({
          layout: 'border',
          items: [{
            region: 'center',
            xtype: 'panel',
            layout: 'fit',
            border: false,
            items: this.activitiesGrid
          }]
        })
      ];

      // load store on activate
      this.on('activate', function(panel){
        panel.store.load({});
      });

      Tine.Document.DocumentsTabPanel.superclass.initComponent.call(this);
    }
});

/************************* helper *********************************/

/**
 * @private Helper class to have activities processing in the standard form/record cycle
 */
Tine.Document.DocumentsFormField = Ext.extend(Ext.form.Field, {
    /**
     * @cfg {Ext.data.JsonStore} recordDocumentsStore a store where the record notes are in.
     */
    recordDocumentsStore: null,

    name: 'notes',
    hidden: true,
    hideLabel: true,

    /**
     * @private
     */
    initComponent: function() {
        Tine.widgets.activities.DocumentsFormField.superclass.initComponent.call(this);
        this.hide();
    },
    /**
     * returns notes data of the current record
     */
    getValue: function() {
        var value = [];
        this.recordDocumentsStore.each(function(note){
        	value.push(note.data);
        });
        return value;
    },
    /**
     * sets notes from an array of note data objects (not records)
     */
    setValue: function(value){
        this.recordDocumentsStore.loadData(value);
    }

});

/**
 * get type icon
 *
 * @param   id of the note type record
 * @returns img tag with icon source
 *
 * @todo use icon_class here
 */
Tine.Document.getMimeTypeIcon = function(mimetype) {
    var icon = "unknown.png";
    switch(mimetype) {
        case 'image/png':
        case 'image/gif':
        case 'image/jpg':
        case 'image/jpeg':
        case 'image/svg+xml':
            icon = "image.png";
            break;
        case 'application/pdf':
            icon = "pdf.png";
            break;
    }
    return '<img src="' + Sopen.Config.runtime.resourceUrl.sopen.url + 'images/oxygen/16x16/mimetypes/' + icon +'" ext:qtip="' + mimetype + '"/>';
    return mimetype;
    var typesStore = Tine.widgets.activities.getTypesStore();
    var typeRecord = typesStore.getById(id);
    if (typeRecord) {
        return '<img src="' + Sopen.Config.runtime.resourceUrl.sopen.url + typeRecord.data.icon + '" ext:qtip="' + typeRecord.data.description + '"/>';
    } else {
    	return '';
    }
};

/**
 * get download link
 *
 * @param   id of the note type record
 * @returns img tag with icon source
 *
 * @todo use icon_class here
 */
Tine.Document.getDownloadLink = function(id, metadata, record) {
    return '<a href="index.php?method=Document.content&docid=' + id + '" target="_blank">Download</a>';
};

/**
 * get preview link
 *
 * @param   id of the note type record
 * @returns img tag with icon source
 *
 * @todo use icon_class here
 */
Tine.Document.getPreviewImage = function(id, metadata, record) {
// record.data.version has the version of the document
    return '<img src="index.php?method=Document.preview&docid=' + record.id + '" ext:qtip="' + id + '"/>';
};

/**
 * get type icon
 *
 * @param   id of the note type record
 * @returns img tag with icon source
 *
 * @todo use icon_class here
 */
Tine.Document._getMimeTypeIcon = function(mimetype) {
    return '<img src="' + Sopen.Config.runtime.resourceUrl.sopen.url + '" ext:qtip="' + mimetype + '"/>';
};

