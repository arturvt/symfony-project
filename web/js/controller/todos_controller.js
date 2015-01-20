/**
 * Created by Artur on 1/6/15.
 */
Todos.TodosController = Ember.ArrayController.extend({
    actions: {
        createTodo: function () {
            "use strict";
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
        },
        clearCompleted: function () {
            "use strict";
            var completed = this.filterBy('isCompleted', true);
            completed.invoke('deleteRecord');
            completed.invoke('save');
        }
    },
    hasCompleted: function() {
      return this.get('completed') > 0;
    }.property('completed'),

    completed: function() {
        return this.filterBy('isCompleted', true).get('length');
    }.property('@each.isCompleted'),

    remaining: function () {
        "use strict";
        return this.filterBy('isCompleted', false).get('length');
    }.property('@each.isCompleted'),

    inflection: function () {
        "use strict";
        var remaining = this.get('remaining');
        return remaining === 1 ? 'item' : 'items';
    }.property('remaining'),
    allAreDone: function (key, value) {
      // If no value argument is passed then this property is being
      // used to populate the current value.
      if (value === undefined) {
        return !!this.get('lenght') && this.isEvery('isCompleted');
      } else {
        this.setEach('isCompleted', value);
        this.invoke('save');
        return value;
      }

    }.property('@each.isCompleted')
});

// Using _ on Todo_ to avoid PHPStorm "thinking" that it is a dev TODO_
