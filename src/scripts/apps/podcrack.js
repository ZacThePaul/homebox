const podcrackSearch = document.getElementById('podcrack-search');
const podcrackSubmit = document.getElementById('podcrack-submit');
const podcastContainer = document.getElementById('podcast-container');

podcrackSubmit.addEventListener('click', function() {

    podcastContainer.innerHTML = "";

    let podcrackSearchQuery = homeboxUrl + '/apps/podcrack/podcast/search?search-query=' + podcrackSearch.value.replace(' ', '+');

    fetch(podcrackSearchQuery)
        .then(response => response.json())
        .then(data => {

            for (let show of data.feeds){

                podcastContainer.innerHTML += 
                    '<div class="results-podcast">'+
                        '<div class="results-podcast-details">'+
                            '<img class="results-podcast-artwork" src="' + show.artwork + '">'+
                            '<h3 class="results-podcast-title">'+ show.title+'</h3>'+
                            '<p class="results-podcast-author">'+ show.author+'</p>'+
                        '</div>'+
                        '<div class="results-podcast-view-episodes">'+
                            '<a href="/apps/podcrack/podcast/view?object=' + encodeURIComponent(JSON.stringify(show)) + '">View Podcast</a><i class="fa-regular fa-chevron-right"></i>'+
                        '</div>'+
                    '</div>';

            }

        })
        .catch(error => {
            console.error('Error:', error);
        });
});