<h3 class="float-right">
    @if(auth()->user()->role == "Company")
        <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Route" onclick="create()">
            <i class="fas fa-plus fa-2xl"></i>
        </a>
    @endif
</h3>