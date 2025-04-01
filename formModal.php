<div class="modal fade" id="userModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Adding or Updating User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="post" enctype="multipart/form-data" id="addform">
                <div class="modal-body">
                    <!-- name -->
                    <div class="row">
                        <div class="py-1"><label for="username">Name:</label></div>
                        <div class="col-10">
                            <span class="bg-dark text-light py-2 px-2"><i class="fa-solid fa-user"></i></span>

                            <input type="text"
                                class="col-10 py-1" name="username" id="username" placeholder="Enter your name" autocomplete="off" required="required">
                        </div>
                    </div>
                    <!-- email -->
                    <div class="row">
                        <div class="py-1"><label for="email">Email:</label></div>
                        <div class="col-10">
                            <span class="bg-dark text-light py-2 px-2"><i class="fa-solid fa-envelope"></i></span>

                            <input type="email"
                                class="col-10 py-1" name="email" id="email" placeholder="Enter your email">
                        </div>
                    </div>
                    <!-- mobile -->
                    <div class="row">
                        <div class="py-1"><label for="number">Mobile Number:</label></div>
                        <div class="col-10">
                            <span class="bg-dark text-light py-2 px-2"><i class="fa-solid fa-phone"></i></span>

                            <input type="text"
                                class="col-10 py-1" name="number" id="number" placeholder="Enter your number" maxlength="10" minlength="10">
                        </div>
                    </div>
                    <!-- photo -->
                    <div class="row">
                        <div class="py-1"><label for="photo">Photo:</label></div>
                        <div class="col-10">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control col-10 py-1" id="photo" name="photo">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="submit" name="submit"class="btn btn-secondary bg-dark text-light">Submit</button>
                    <button type="submit" class="btn btn-secondary bg-danger" data-bs-dismiss="modal">Close</button>

                    <input type="hidden" name="action" value="adduser">
                    <input type="hidden" name="userId" id ="userId" value="">
                </div>
            </div>
        </form>
    </div>
</div>