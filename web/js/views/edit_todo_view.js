// Component to handle inserts after editing
Todos.EditTodoView =  Ember.TextField.extend({
    didInsertElement: function () {
        this.$().focus();
    }
});

// Register the component using handlebars.
Ember.Handlebars.helper('edit-todo', Todos.EditTodoView);