{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    <script src="{{ asset('vendor/backpack/jquery-mask/jquery.mask.min.js') }}"></script>
    <script>
        function show_hide_individual_fields(contact_type, parent_id, job_position, first_name, last_name) {
            let show = (contact_type.val() === "individual");
            if (show) {
                first_name.parent().show();
                last_name.parent().show();
                parent_id.parent().show();
                job_position.parent().show();
            } else {
                first_name.parent().hide();
                last_name.parent().hide();
                parent_id.parent().hide();
                job_position.parent().hide();
            }
        }
        function show_hide_company_fields(contact_type, company_name) {
            let show = (contact_type.val() === "company");
            if (show) {
                company_name.parent().show();
            } else {
                company_name.parent().hide();
            }
        }
        function update_contact_type(identification_type_id, contact_type) {
            let contact_type_var = identification_type_id.val() === "2" ? "company" : "individual";
            contact_type.val(contact_type_var);
        }
        function set_identification_input_mask(identification_type_id, identification, clean = true) {
            if (clean) {
                identification.val('');
            }
            let value = identification_type_id.val();
            if (value === '1') {
                identification.mask('0-0000-0000');
                identification.attr("placeholder", '0-0000-0000');
            } else if (value === '2') {
                identification.mask('3-100-000000');
                identification.attr("placeholder", '3-101-000000');
            } else if (value === '3') {
                identification.mask('1000000000');
                identification.attr("placeholder", '1000000000');
            } else if (value === '4') {
                identification.mask('0000000000');
                identification.attr("placeholder", '0000000000');
            } else if (value === '5') {
                identification.unmask();
                identification.attr("placeholder", '');
            }
        }
        $(document).ready(function () {
            // elements
            const $form = $("input[name='_token']").parent();
            const $contact_type = $("input[name='contact_type']");
            const $identification_type_id = $("select[name='identification_type_id']");
            const $identification = $("input[name='identification']");
            const $parent_id = $("select[name='parent_id']");
            const $job_position = $("input[name='job_position']");
            const $is_client = $('#is_client');
            const $is_provider = $("#is_provider");
            const $first_name = $("input[name='first_name']");
            const $last_name = $("input[name='last_name']");
            const $company_name = $("input[name='company_name']");
            //init
            show_hide_individual_fields($contact_type, $parent_id, $job_position, $first_name, $last_name);
            show_hide_company_fields($contact_type, $company_name);
            set_identification_input_mask($identification_type_id, $identification, false);
            //listeners
            $identification_type_id.on('change', function () {
                update_contact_type($identification_type_id, $contact_type);
                set_identification_input_mask($identification_type_id, $identification);
                show_hide_individual_fields($contact_type, $parent_id, $job_position, $first_name, $last_name);
                show_hide_company_fields($contact_type, $company_name);
            });
            $is_client.change(function () {
                //not implemented yet
            });
            $is_provider.change(function () {
                //not implemented yet
            });
            //before submitting
            $form.on("submit", function () {
                if ($contact_type.val() === 'company') {
                    $parent_id.val('');
                    $job_position.val('');
                    $first_name.val('');
                    $last_name.val('');
                }else{
                    $company_name.val('');
                }
            });
        });
    </script>
@endpush
