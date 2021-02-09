const categoria=document.getElementById('categoria');
if (categoria){
    categoria.addEventListener('click',e=>{
        if (e.target.className==='btn btn-danger delete'){
           if (confirm("Tem certeza")){
            const id=e.target.getAttribute('data-id');
            fetch(`/delete/${id}`,{
                method:'DELETE'
            }).then(res=>window.location.reload())
           }
        }
    })
}
const post=document.getElementById('post');
if (post){
    post.addEventListener('click',e=>{
        if (e.target.className==='btn btn-danger delete'){
            if (confirm("Tem certeza")){
                const id=e.target.getAttribute('data-id');
                fetch(`/delete/${id}`,{
                    method:'DELETE'
                }).then(res=>window.location.reload())
            }
        }
    })
}