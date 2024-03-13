let searchField = document.getElementById("search-bar");
let searchResults = document.getElementById("search-results");

let deleteButtons=document.getElementsByClassName('delete-button');

(function(){
    for(let i=0;i<deleteButtons.length;++i){
        deleteButtons[i].addEventListener('click',(e)=>{
            let defID=e.target.parentNode.parentNode.id;
            let parent=e.target.parentNode.parentNode;
            parent.parentNode.removeChild(parent);
            fetch(`http://localhost/Projekt_zaliczeniowy/delete.php`,{
                method:"POST",
                body:JSON.stringify({idToDelete:defID}),
                headers:{"Content-Type":"Application/json"},
            }).then((response)=>response.json())
                    .then((data)=>{
                        console.log("Success:",data);
            }).catch((error)=>{
                console.log("Error");
            })
            
        })
    }
})()



//funkcja wysyłająca zapytanie do servera po wpisaniu literki
searchField.addEventListener("input", () =>
        {
            let q = searchField.value;
            console.log(q);
            let url = `http://localhost/Projekt_zaliczeniowy/gethint.php?q=${q}`;
            fetch(url)
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        addHintsToDOM(data);
                    });

        })

function addHintsToDOM(elements) {
    let oldUl = document.getElementById('search-links');

    let ul = document.createElement('ul');
    for (const element of elements) {
        let li = document.createElement('li');
        let a=document.createElement('a');
        a.href=`define.php?term=${element}`;
        a.textContent=element;
        li.appendChild(a);
        ul.appendChild(li);
    }
    ul.classList.add('search-links');
    ul.setAttribute('id', 'search-links');
    if (oldUl == null) {
        searchResults.appendChild(ul);
    } else
        searchResults.replaceChild(ul, oldUl);
}




