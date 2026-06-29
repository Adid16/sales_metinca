<div class="modal-header bg-warning py-2">
    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update User</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('users.update',$user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="customername">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" id="customername"
                placeholder="Name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group">
            <label for="companyname">Company</label>
            <input type="text" name="company" value="{{ old('company',$user->company) }}" class="form-control form-control-sm" id="customername"
                placeholder="Company">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" value="{{ old('email',$user->email) }}" class="form-control form-control-sm" id="email"
                placeholder="Email">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control form-control-sm" id="password"
                placeholder="Password">
        </div>
        {{-- <input type="text" name="role" value="customer"> --}}
        <div class="form-group">
            <label for="role">Role</label>
            {{-- <input type="text" name="role" value="Customer" class="form-control form-control-sm" id="role" readonly> --}}
            <fieldset class="form-group">
                <select class="form-select form-select-sm" id="basicSelect" name="role">
                    <option selected value="customer">Customer</option>
                </select>
            </fieldset>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm">Save</button>
        </div>
    </form>
</div>
