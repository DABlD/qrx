<h3 class="float-right">
    <a class="btn btn-success btn-sm" data-toggle="tooltip" title="Export" onclick="exportToExcel()">
        <i class="fas fa-file-excel"></i>
    </a>
</h3>
<br><br>

<div class="row">
    <div class="col-md-3">
        <div class="row iRow">
            <div class="col-md-4 iLabel" style="margin: auto;">
                From
            </div>
            <div class="col-md-8 iInput">
                <input type="text" id="from" clas="form-control">
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="row iRow">
            <div class="col-md-4 iLabel" style="margin: auto;">
                To
            </div>
            <div class="col-md-8 iInput">
                <input type="text" id="to" clas="form-control">
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <div class="row iRow">
            <div class="col-md-4 iLabel" style="margin: auto;">
                Status
            </div>
            <div class="col-md-8 iInput">
                <select id="status" class="form-control">
                    <option value="%%">All</option>
                    <option value="Ticket Generated">Ticket Generated</option>
                    <option value="Embarked">Embarked</option>
                    <option value="Disembarked">Disembarked</option>
                </select>
            </div>
        </div>
    </div>
</div>