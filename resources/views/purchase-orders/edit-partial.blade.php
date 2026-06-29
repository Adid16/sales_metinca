<div class="modal-header" style="background:lightblue;">
    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit PO</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('purchase-orders.update',$purchase_order->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="p-2">
            <label for="selectStatus" class="">Status</label>
            <select name="status" id="selectStatus" class="form-control">
                <option value="">Pilih status</option>
                <option value="production">Production</option>
                <option value="ship">Ship</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
    </div>
</form>
