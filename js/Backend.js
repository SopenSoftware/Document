Ext.ns('Tine.Document');

Tine.Document.documentBackend = new Tine.Tinebase.data.RecordProxy({
	   appName: 'Document',
	   modelName: 'Document',
	   recordClass: Tine.Document.Model.Document
});

