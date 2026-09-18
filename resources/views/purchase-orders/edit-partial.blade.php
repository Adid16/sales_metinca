<div class="modal-header bg-primary text-white">
    <h5 class="modal-title text-white" id="staticBackdropLabel"><i class="bi bi-pencil-square me-2"></i>Edit PO</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('purchase-orders.update',$purchase_order->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="p-2">
            <label for="selectStatus" class="">Status</label>
            <select name="status" id="selectStatus" class="form-control">
                <option value="production" {{ $purchase_order->status == 'production' ? 'selected' : '' }}>Production</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
    </div>
</form>
