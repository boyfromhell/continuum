window.Tribute = require('tributejs');

axios.get('/api/users')
    .then(function (response) {
        let tribute = new Tribute({
            // column to search against in the object (accepts function or string)
            lookup: 'key',

            // column that contains the content to insert by default
            fillAttr: 'value',

            // REQUIRED: array of objects to match or a function that returns data (see 'Loading remote data' for an example)
            values: response.data,
        });

        tribute.attach(document.querySelectorAll(".mentionable"));
    })
