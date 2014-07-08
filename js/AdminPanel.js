Ext.namespace('Tine.Document');

/**
 * @namespace Tine.Document.Model
 * @class Tine.Document.Model.Settings
 * @extends Tine.Tinebase.data.Record
 */
Tine.Document.Model.Settings = Tine.Tinebase.data.Record.create([
  { name: 'id' },
  { name: 'defaults' },
  { name: 'fundproject_id', type: 'int' },
  { name: 'addressbook_id', type: 'int' },
  { name: 'membership_id', type: 'int' }
  ], {
  appName: 'Document',
  modelName: 'Settings',
  idProperty: 'id',
  titleProperty: 'title',
  recordName: 'Settings',
  recordsName: 'Settingss',
  containerProperty: 'container_id',
  containerName: 'Settings',
  containersName: 'Settings',

  getTitle: function() {
      return this.recordName;
  }
});


/**
 * @namespace Tine.Document
 * @class Tine.Document.settingBackend
 * @extends Tine.Tinebase.data.RecordProxy
 */
Tine.Document.settingsBackend = new Tine.Tinebase.data.RecordProxy({
  appName: 'Document',
  modelName: 'Settings',
  recordClass: Tine.Document.Model.Settings
});

Tine.Document.AdminPanel = Ext.extend(Tine.widgets.dialog.EditDialog, {
  /**
   * @private
   */
  appName: 'Document',
  recordClass: Tine.Document.Model.Settings,
  recordProxy: Tine.Document.settingsBackend,
  evalGrants: false,

  /**
   * returns dialog
   *
   * NOTE: when this method gets called, all initalisation is done.
   *
   * @return {Object}
   * @private
   */
  getFormItems: function() {
    return {
      xtype: 'panel',
      title: 'Einstellungen',
      layout: 'form',
      padding: 10,
      items: [
        {
          xtype: 'numberfield',
          fieldLabel: 'Adressbuch ID',
          name: 'addressbook_id'
        }, {
          xtype: 'numberfield',
          fieldLabel: 'Förderprojekt ID',
          name: 'fundproject_id'
        }, {
          xtype: 'numberfield',
          fieldLabel: 'Mitgliedschaft ID',
          name: 'membership_id'
        }
      ]
    };
  }
});

/**
 * admin panel on update function
 */
Tine.Document.AdminPanel.onUpdate = function() {
    // reload mainscreen to make sure registry gets updated
    window.location = window.location.href.replace(/#+.*/, '');
};

/**
 * Open window dialog
 *
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Document.AdminPanel.openWindow = function (config) {
  var id = (config.record && config.record.id) ? config.record.id : 0;
  var window = Tine.WindowFactory.getWindow({
    width: 600,
    height: 400,
    name: Tine.Document.AdminPanel.prototype.windowNamePrefix + id,
    contentPanelConstructor: 'Tine.Document.AdminPanel',
    contentPanelConstructorConfig: config
  });

  return window;
};