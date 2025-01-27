<section>
    <div class="center">
        <h2>Capture contacts</h2>
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a class="active"  href="#general_contact">General</a></li>
                    <li class="tab col s3"><a href="#contact_clients">Clients</a></li>
                </ul>
            </div>
            <div id="general_contact" class="col s12">
                <div class="row">
                    <form class="col s12" id="create_client_form">
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="contact_name" type="text" name="name" class="validate">
                                <label for="contact_name">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="contact_surname" type="text" name="surname" class="validate">
                                <label for="contact_surname">Surname</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="contact_email" type="email" name="email" class="validate">
                                <label for="contact_email">Email</label>
                            </div>
                        </div>
                        <div class="row">
                            <div id="contact_link_trigger" class="col s4 text-left hidden">
                                <a class="waves-effect waves-light modal-trigger" href="#contact_modal">Link client</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s4">
                                <button class="btn btn-large orange" type="submit">
                                    Submit
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                        <div id="create_spinner" class="hidden">Loading...</div>
                    </form>
                </div>
                <div class="row">
                    <div id="contact_modal" class="modal">
                        <div class="modal-content">
                        <form class="col s12" id="link_contact_form">
                            <div class="row">
                                <div class="input-field col s12">
                                    <select multiple id="client_codes">
                                        <option disabled selected>Choose clients</option>
                                        <option value="1">Contact 1</option>
                                        <option value="2">Contact 2</option>
                                        <option value="3">Contact 3</option>
                                    </select>
                                    <label>Clients</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="modal-footer col s6">
                                    <a href="#!" class="modal-close waves-effect waves-green btn-flat orange" id="link_client">
                                        Link
                                        <i class="material-icons right">send</i>
                                    </a>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

            </div>
            <div id="contact_clients" class="col s12">
                Clients
            </div>
        </div>
    </div>
</section>

