const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })

function deleteProduct(id) {
    console.log(id);
    Swal.fire({
        title: 'êtes-vous sûrs ? ',
        text: "Voulez-vous vraiment supprimer ce produit ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, Supprimer!'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "/admin/produit/"+id,
                
                success: function()
                {
                    alert("hhhhhhhhh");
                }
            })
        }
      })
}

function showMessage(message)
{
  Toast.fire({
      icon: 'success',
      title: message
    })
  
}