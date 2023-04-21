const container = document.querySelector('#container');
let url = 'http://127.0.0.1:8000/api/article/all';
// async function getArticles() {
//     const json = await fetch(url);
//     const articles = await json.json();
//     articles.forEach(obj => {
//         console.log(obj);
//         const article = document.createElement('div');
//         container.appendChild(article);
//         article.setAttribute('id', obj.id);
//         const titre = document.createElement('h1');
//         titre.innerText = obj.titre;
//         const contenu = document.createElement('p');
//         contenu.innerText = obj.contenu;
//         article.appendChild(titre);
//         article.appendChild(contenu);


//     });

    
//     console.log(articles);
 
// }
// getArticles();

let body=document.querySelector('body');

    



setTimeout(()=>{
    fetch(url)
        .then(async response =>{
            const data = await response.json();
            console.log(data);
            console.log(response.status);
            if (response.status === 200) {
                data.forEach(obj => {
                    console.log(obj);
                    const article = document.createElement('div');
                    container.appendChild(article);
                    article.setAttribute('id', obj.id);
                    const titre = document.createElement('h1');
                    titre.innerText = obj.titre;
                    const contenu = document.createElement('p');
                    contenu.innerText = obj.contenu;
                    const date = document.createElement('p');
                    date.innerText = obj.date;
                    article.appendChild(titre);
                    article.appendChild(contenu);
                    article.appendChild(date);
            
            
                });
            }
        });
        //console.log('chargé')
   
},1000);
