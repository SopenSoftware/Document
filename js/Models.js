Ext.ns('Tine.Document','Tine.Document.Model');

Tine.Document.Model.DocumentArray = Tine.Tinebase.Model.genericFields.concat([
    {name: 'id'},
    {name: 'version'},
    {name: 'name'},
    {name: 'comment'},
    {name: 'creation_date', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    {name: 'mimetype'}
]);

Tine.Document.Model.Document = Tine.Tinebase.data.Record.create(Tine.Document.Model.DocumentArray, {
   appName: 'Document',
   modelName: 'Document',
   idProperty: 'id',
   titleProperty: 'name',
   recordName: 'Document',
   recordsName: 'Documents',
//   containerProperty: 'container_id',
 //  containerName: 'Record list',
  // containersName: 'Record lists'
});

Tine.Document.Model.Document.getFilterModel = function() {
   var app = Tine.Tinebase.appMgr.get('Document');
      
   return [ 
       {label : _('Quick search'), field : 'query', operators : [ 'contains' ]}, 
       {filtertype : 'tine.widget.container.filtermodel', app : app
           , recordClass : Tine.Document.Model.Document}, 
       {filtertype : 'tinebase.tag', app : app} 
   ];
};

