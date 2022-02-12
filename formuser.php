<div class="modal-content" style="display:none" id="formau">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
    </div>
    <form id="form">
        <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="hidden" name="id" id="inputid" >
                    <input type="text" name="nama" id="inputnama" class="form-control" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" id="inputkelas" class="form-control" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <select type="text" name="jurusan" id="inputjurusan" class="form-control">
                        <option value="">--- Please Select ---</option>
                        <option value="RPL">Rekayasa Perangkat Lunak</option>
                        <option value="TKJ">Teknik Komputer Jaringan</option>
                        <option value="MM">Multimedia</option>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btback">Back</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</div>