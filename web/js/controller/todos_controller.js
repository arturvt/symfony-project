/**
 * Created by Artur on 1/6/15.
 */
Todos.TodosController = Ember.ArrayController.extend({
    actions: {
        createTodo: function() {
            // Get the todo_ title set by the "New Todo_" text field
            var title = this.get('newTitle');
            if (!title.trim()) { return; }

            // Create the new Todo_ model
            var todo = this.store.createRecord('todo', {
                title: title,
                isCompleted: false
            });

            // Clear the "New Todo_" text field
            this.set('newTitle', '');

            // Save the new model
            todo.save();
        }
    },
    remaining: function() {
        return this.filterBy('isCompleted', false).get('length');
    }.property('@each.isCompleted'),

    inflection: function() {
        var remaining = this.get('remaining');
        return remaining === 1 ? 'item' : 'items';
    }.property('remaining')
});

// Using _ on Todo_ to avoid PHPStorm "thinking" that it is a dev TODO_