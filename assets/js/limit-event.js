

let select = document.getElementById('paginationSelect');
select.addEventListener('change', function() {
    var url = new URL(window.location.href);
    url.searchParams.set('limit', this.value);
    window.location.href = url.href;
});