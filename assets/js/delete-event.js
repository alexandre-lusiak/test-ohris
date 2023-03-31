const deleteButtons = document.querySelectorAll('.btn-delete');
const deleteModal = document.getElementById('deleteModal');
const confirmButton = document.getElementById('confirm-delete');
const cancelButton = document.getElementById('cancel-delete');

deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
        console.log('okok');
        const eventId = button.getAttribute('data-id');
        console.log(eventId);
        deleteModal.style.display = 'block';
        console.log(deleteModal.style.display);
        confirmButton.addEventListener('click', () => {
            fetch(`/event/delete/${eventId}`, {
                method: 'DELETE'
            })
            .then(() => {
                window.location.replace('/admin');
            })
            .catch(error => {
                console.error(error);
            });
        });

        cancelButton.addEventListener('click', () => {
            deleteModal.style.display = 'none';
        });
    });
});

window.addEventListener('click', event => {
    if (event.target === deleteModal) {
        deleteModal.style.display = 'none';
    }
});