window.Todos = Ember.Application.create();
// uses the LocalStorage Adapter
Todos.ApplicationAdapter = DS.LSAdapter.extend({
  namespace: 'todos-emberjs'
});
