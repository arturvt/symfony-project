Todos.TodoController = Ember.ObjectController.extend({
    actions: {
        editTodo: function () {
            this.set('isEditing', true);
        },
        acceptChanges: function () {
            // change editing status to false
            this.set('isEditing', false);

            // If nothing changed, calls remove todo_ function.
            if (Ember.isEmpty(this.get('model.title'))) {
                this.send('removeTodo');
            } else {
                // saves.
                this.get('model').save();
            }
        },
        removeTodo: function () {
            var todo = this.get('model');
            todo.deleteRecord();
            todo.save();
        }
    },

    isEditing: false,

    isCompleted: function (key, value) {
        var model = this.get('model');
        if (value === undefined) {
            //property being used as a getter
            return model.get('isCompleted');
        } else {
            // property being used as a setter
            model.set('isCompleted', value);
            model.save();
            return value;
        }
    }.property('model.isCompleted')
});
