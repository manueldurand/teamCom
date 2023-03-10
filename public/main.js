document.querySelectorAll("a.js-like").forEach(function(link){
    link.addEventListener('click', likeOrUnlike);
});
//met à jour le nombre de vues du post-todo et change l'icone, plein quand il est vu et vide sinon
function likeOrUnlike(event){
    event.preventDefault();
    const url = this.href;
    const spanCount = this.querySelector('span.js-vues');
    const icone = this.querySelector('i');
    axios.get(url).then(function(response) {
        const views = response.data.views;
        spanCount.textContent = views;
        if(icone.classList.contains('fas')){
            icone.classList.replace('fas', 'far')
        } else {
            icone.classList.replace('far', 'fas');
        }
        console.log(response.data.message)
    })

}

//utiliser 'this' permet de cibler un élément du link ciblé polus haut, donc tout le lien <a>
// source youtube lior Chamla vidéo AJAX & SYMFONY Initiation
