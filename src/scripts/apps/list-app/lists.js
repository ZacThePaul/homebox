function getLists() {

    fetch('/apps/lists/get_all')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {

            // self lists
            for (let i = 0; i < data.list.length; i++) {

                const id = data.list[i]['@attributes']['user_id'];

                console.log(typeof(id) + typeof(document.getElementById('session-user-id').value));

                if (id != document.getElementById('session-user-id').value) {
                    continue;
                }

                const name = data.list[i].name;
                let items = data.list[i].items.item;

                // Check if items is not an array
                if (!Array.isArray(items)) {
                    items = [items]; // Convert it into an array with a single element
                }

                let listItemsStr = '';

                for (let item of items) {
                    listItemsStr += `<li class="self-list-item">${item}</li>`;
                }

                const newElement = `
                <div class="self-list">
                    <h5 class="self-list-item-title">${name}</h5>
                    <ul class="self-list">
                        ${listItemsStr}
                    </ul>
                </div>
                `

                document.getElementById('self-lists-container').innerHTML += newElement;

            }

            // community lists
            for (let i = 0; i < data.list.length; i++) {

                const community = parseInt(data.list[i]['@attributes']['community']);

                if (community != 1) {
                    continue;
                }

                const id = data.list[i]['@attributes']['user_id'];
                const name = data.list[i].name;
                let items = data.list[i].items.item;

                // Check if items is not an array
                if (!Array.isArray(items)) {
                    items = [items]; // Convert it into an array with a single element
                }

                let listItemsStr = '';

                for (let item of items) {
                    listItemsStr += `<li class="self-list-item">${item}</li>`;
                }

                const newElement = `
                <div class="self-list-item">
                    <h5 class="self-list-item-title">${name}</h5>
                    <ul class="self-list">
                        ${listItemsStr}
                    </ul>
                </div>
                `

                document.getElementById('community-lists-container').innerHTML += newElement;

            }

        })
        .catch(error => {
            // Handle error
            console.error('Fetch error:', error);
        });
}

function addNewList() {
    const newListBtn = document.getElementById('add-new-list-button');

    newListBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const listIndex = document.getElementsByClassName('list-index-container')[0];
        const listAdd = document.getElementsByClassName('list-add-new-container')[0];
        const listEdit = document.getElementsByClassName('list-edit-container')[0];

        listIndex.classList.remove('show');
        listEdit.classList.remove('show');
        listAdd.classList.add('show');

        listIndex.classList.add('hide');
        listEdit.classList.add('hide');
        listAdd.classList.remove('hide');

    })
}

window.addEventListener('load', function() {

    getLists();
    addNewList();

    // other stuff

    let listItems = [];

    const addButton = document.getElementById('add-list-item');
    const listContainer = document.getElementById('list-item-container');
    let currentActiveListItem = document.getElementsByClassName('active-list-item')[0];

    // Runs onMount
    currentActiveListItem.addEventListener('blur', function(event) {
        listItems.push(this.textContent);
        // console.log(currentActiveListItem.textContent)
    });

    const newItem = `
        <ul>
            <label for="">Add your list item</label>
            <li contenteditable="true" class="active-list-item"></li>
            <button class="delete-list-item">Delete</button>
        </ul>
        `;

    addButton.addEventListener('click', function() {

        for (let x = 0; x < listContainer.getElementsByTagName('li').length; x++) {
            listContainer.getElementsByTagName('li')[x].contentEditable = false;

            listContainer.getElementsByTagName('li')[x].classList.remove('active-list-item');

            if (listContainer.getElementsByTagName('li')[x].previousElementSibling) {
                listContainer.getElementsByTagName('li')[x].previousElementSibling.remove();
            }

            currentActiveListItem = listContainer.getElementsByTagName('li')[x];
        }

        listContainer.innerHTML += newItem;

        for (let x = 0; x < deleteButtons.length; x++) {
            const item = document.getElementsByClassName('delete-list-item')[x];

            item.addEventListener('click', function(e) {

                const listItemText = e.target.previousElementSibling.textContent;

                // Filter out the list item with the matching text
                listItems = listItems.filter(item => item !== listItemText);

                // console.log(listItems); // To check the updated list

                e.target.closest('ul').remove();
            })
        }

        document.getElementsByClassName('active-list-item')[0].addEventListener('blur', function(event) {
            listItems.push(this.textContent);
        });

    });

    deleteButtons = document.getElementsByClassName('delete-list-item');
    
    for (let x = 0; x < deleteButtons.length; x++) {
        const item = document.getElementsByClassName('delete-list-item')[x];

        item.addEventListener('click', function(e) {
            console.log('test')

            e.target.closest('ul').remove();
            const itemText = e.target.closest('li').textContent;

            // listItems[itemText];
        })
    }

    document.getElementById('save-list').addEventListener('click', function(e) {
        e.preventDefault();

        // turn list elements into object
        const listObj = {
            'name': document.getElementById('list-name').value,
            'userId': document.getElementById('session-user-id').value,
            'listItems': listItems
        }
        
        fetch('/apps/lists/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(listObj),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {
            // if the saving was successful
            if ( JSON.parse(data) ) {

                window.location.href = "http://192.168.1.23/apps/lists";

                // document.getElementsByClassName('list-add-new-container')[0].classList.remove('show');
                // document.getElementsByClassName('list-add-new-container')[0].classList.add('hide');

                // document.getElementsByClassName('list-index-container')[0].classList.remove('hide');
                // document.getElementsByClassName('list-index-container')[0].classList.add('show');

            } 
        })
        .catch(error => {
            // Handle error
            console.error('Fetch error:', error);
        });


    })
})