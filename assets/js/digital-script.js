
jQuery(document).ready(function($){

    var newDiv = $(`<div class="wpsci-loader"></div>`);
    $('body').append(newDiv);

    var guests = jQuery('#number_of_guest').val();
    if(guests==1){
      jQuery(`.guest_type #house1 option[value='16']`).prop('selected', true);
      jQuery(`.guest_type #house1`).prop('readonly', true).css('pointer-events', 'none');
    }else{
      jQuery(`.guest_type #house1 option[value='18']`).prop('selected', true);
      jQuery('#house1 option[value="16"]').remove();
    }


    // Define an array to store the old inputs
    var oldInputs = [];

    jQuery(document).on('change', '.doc_img', function(event) {
        var input = jQuery(this);
        var previewContainer = input.closest('.image-preview-container').find('.image-preview');
        var oldImagesContainer = input.closest('.image-preview-container').find('.old-images');

        // Concatenate old inputs with new ones
        var allFiles = oldInputs.concat(Array.from(event.target.files));

        oldInputs = allFiles;
        var dataTransfer = new DataTransfer();
        allFiles.forEach(function(file) {
            dataTransfer.items.add(file);
        });
        var fileList = dataTransfer.files;
        input.get(0).files = fileList;
        previewContainer.empty().html(oldImagesContainer);

        // Append preview items for all files
        Array.from(fileList).forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewHTML = '<div class="image-preview-item">';
                previewHTML += '<img class="preview-image" src="' + e.target.result + '">';
                previewHTML += '<span class="remove-btn">✖</span>';
                previewHTML += '</div>';

                previewContainer.append(previewHTML);
            };
            reader.readAsDataURL(file);
        });
    });

    
    jQuery(document).on('click', '.remove-btn', function() {

        if(jQuery(this).hasClass('old-img')){
            console.log('has');
            // If it is from old images
            var trigger = jQuery(this);
            var val = trigger.data('val');
            var removed_image = trigger.closest('.image-preview-container').find('.removed_images');
            if(removed_image.val()==""){
                removed_image.val(val);
            }else{
                var new_value = removed_image.val()+","+val;
                removed_image.val(new_value);
            }
            trigger.closest('.image-preview-item').remove();
        }else{
            var input = jQuery(this).closest('.image-preview-container').find('.doc_img');
            var indexToRemove = jQuery(this).closest('.image-preview-item').index();
        
            removeFileAtIndex(input, indexToRemove);
            jQuery(this).closest('.image-preview-item').remove();
        }
    });
    
    
    // For remove of div in edit form of guest information
    jQuery(document).on('click','.remove-sign',function(){
        var id = "#"+jQuery(this).data('id');

        jQuery(id).hide(200, function() {
            jQuery(this).remove();
            updateGuestCountIndex();
        });
        
        jQuery(this).closest('.guest-information-heading').hide(400, function() {
            jQuery(this).remove();
            updateGuestCountIndex();
        });
    });

    //for adding fieds for guest
    jQuery(document).on('click','.add-guest',async function(){
        $('body').addClass('load-overlay');
        //count number of rows
        var lastTableCount  = jQuery('.table-count').last();
        var index = lastTableCount.data('count');
        var count = jQuery('.table-count').length;
        var url = $(this).data('path');

        var html = await generate_add_row_html(index+1,count+1, url);
        
        jQuery('.additional-table-rows').append(html);
        setTimeout(() => {
            $('body').removeClass('load-overlay');
        }, 500);
    });

    //
    jQuery('.remove-uploaded-img').on('click', function(){
        var id = jQuery(this).data('id');
        jQuery(`#doc_img_real${id}`).val('');
        jQuery(this).closest('.doc-image-wrapper').find('.previous-preview-image').remove();
        jQuery(this).remove();
    });

    //ajax reuqest 
    jQuery('#updated_booking_id').on('input', function(){
        var element = jQuery(this);
        var id = jQuery(this).val();

        if(element.data('id') == id){
            element.next('.field-alert').addClass('hide');
            jQuery('button[name="update_overview"]').prop('disabled', false);
            return;
        }
        
        jQuery.ajax({
            url: wpsci.ajaxurl,
            method: 'POST',
            data: {
                action: 'check_booking_id',
                booking_id: id,
            },
            success: function (response) {

                if(response.data=='available'){
                    element.next('.field-alert').addClass('hide');
                    jQuery('button[name="update_overview"]').prop('disabled', false);
                }else{
                    element.next('.field-alert').removeClass('hide');
                    jQuery('button[name="update_overview"]').prop('disabled', true);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error : ', error);
            }
        });
    });

    //ajax reuqest 
    jQuery('#check_in_form').on('submit', function(e){
        e.preventDefault();
        jQuery('.field-alert').addClass('hide');
        $('body').addClass('load-overlay');
        var form = jQuery(this);
        var id = jQuery('#custom_booking_id').val();
        
        jQuery.ajax({
            url: wpsci.ajaxurl,
            method: 'POST',
            data: {
                action: 'check_booking_id',
                booking_id: id
            },
            success: function (response) {
                console.log(response);
                if(response.data=='available'){
                    form.off('submit').submit();
                }else{
                    jQuery('.field-alert').removeClass('hide');
                    $('body').removeClass('load-overlay');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error : ', error);
            }
        });
    });
  	

    //default template
    jQuery('.default-template-btn').click(function(){
        var subject = 'Notification from %site_title%';
        var header = 'Notification for booking #%booking_id%';
        var footer = '123 New street, <br> State, Country';
        var message = `<p>Dear %guest_first_name% %guest_last_name%,</p>
                    <p>&nbsp;</p>
                    <h3>Details of booking #%booking_id%</h3>
                    <p>&nbsp;</p>
                    <p>Check in date %check_in_date%</p>
                    <p>Check out date %check_out_date% &nbsp;</p>
                    <p>&nbsp;</p>
                    <p>Fill this form:<br />%wpsci_form_url%</p>
                    <p>&nbsp;</p>
                    <p>Thank You!</p>
                    <p>Visit again.</p>`;
  
        jQuery('#wpsci_email_subject').val(subject);
        jQuery('#wpsci_email_header').val(header);
        jQuery('#wpsci_email_footer').val(footer);
        var editor = tinymce.get('wpsci_email_message');
        if (editor) {
            editor.setContent(message);
        }
    });

});


function get_country_name(i){
    var val = jQuery('#country_code'+i).find(':selected').attr('data-val');
    jQuery('#country'+i).val(val);
    if(jQuery('#country_code'+i).find(':selected').val()=='100000100'){
        jQuery('#municipal'+i).show();
        jQuery('#provinces'+i).show();
    }else{
        jQuery('#municipal'+i).hide();
        jQuery('#provinces'+i).hide();
        jQuery(`#provinces${i} option:first`).prop('selected',true);
        jQuery(`#municipalities${i} option:first`).prop('selected',true);
    }
}
function get_municipal(i){
    
    var val = jQuery('#provinces'+i).find(':selected').val();

    jQuery.ajax({
        url: wpsci.ajaxurl,
        method: 'POST',
        data: {
            action: 'get_municipal_data',
            province: val
        },
        success: function (response) {
            
            jQuery("#municipalities"+i).html(response.data);
            $('body').removeClass('load-overlay');
        },
        error: function (xhr, status, error) {
            console.error('Error : ', error);
        }
    });
}
function edit_form(){
  	var count = jQuery('#guest_count').val();
  	var sum = +count+1;
  	for(i=2;i<sum;i++){
      jQuery('#house'+i+' option[value="16"]').remove();
      jQuery('#house'+i+' option[value="17"]').remove();
      jQuery('#house'+i+' option[value="18"]').remove();
    }
    jQuery('#edit_wrap').show();
    jQuery('#guest_table').hide();
}
function back_form(){
    jQuery('#edit_wrap').hide();
    jQuery('#guest_table').show();
}
function copyToClipboard(element) {
    var $temp = jQuery("<input>");
    jQuery("body").append($temp);
    $temp.val(jQuery(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
    jQuery('#url_btn2').show();
    jQuery('#url_btn1').hide();
    var myArray = element.split("_");
    var num = myArray[1];
    jQuery('#url_btn_copied'+num).show();
    jQuery('#url_btn_copy'+num).hide();
}
function get_doc_place(i){
    if(jQuery('#doc_issue_place'+i).find(':selected').val()=='100000100'){
        jQuery('#doc_municipal'+i).show();
        jQuery('#doc_provinces'+i).show();
    }else{
        jQuery('#doc_municipal'+i).hide();
        jQuery('#doc_provinces'+i).hide();
        jQuery(`#doc_issue_province${i} option:first`).prop('selected',true);
        jQuery(`#doc_issue_municipality${i} option:first`).prop('selected',true);
    }
}
function get_doc_municipal(i){

    var val = jQuery('#doc_issue_province'+i).find(':selected').val();

    jQuery.ajax({
        url: wpsci.ajaxurl,
        method: 'POST',
        data: {
            action: 'get_municipal_data',
            province: val
        },
        success: function (response) {
            
            jQuery("#doc_issue_municipality"+i).html(response.data);
        },
        error: function (xhr, status, error) {
            console.error('Error : ', error);
        }
    });
}

function open_modal(element){
    jQuery(element).fadeIn();
}
function close_modal(element){
    jQuery(element).fadeOut();
}

function removeFileAtIndex(input, indexToRemove) {
    var files = input.prop('files');
    var newFiles = [];
    for (var i = 0; i < files.length; i++) {
        if (i !== indexToRemove) {
            newFiles.push(files[i]);
        }
    }
    // Create a new FileList object with the updated list of files
    var newFileList = new DataTransfer();
    for (var i = 0; i < newFiles.length; i++) {
        newFileList.items.add(newFiles[i]);
    }
    // Set the files property of the input element with the new FileList object
    input.prop('files', newFileList.files);
}

function updateGuestCountIndex() {
    // Update the count
    jQuery('.guest_count').each(function(index) {
        jQuery(this).text(index + 1);
    });
}

//fetch documents
async function create_option_from_file_read(path , purpose) {
    try {
        const response = await fetch(path);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const fileData = await response.text();
        
        var options='';
        if(purpose == "country_code"){
            const lines = fileData.trim().split('\r\n');

            const formattedData = lines.map(line => {
                const values = line.trim().split(',');
                const obj = {};
                values.forEach((value, index) => {
                    obj[index] = value;
                });
                return obj;
            });
            
            for(i=0;i<formattedData.length;i++){
                var data = formattedData[i];
                options+=`<option value="${data[0]}" data-val="${data[1]}">${data[1]}</option>`;
            }

            return options;
        }else if(purpose == "province_options"){
            // Split file data into lines
            const lines = fileData.trim().split('\n');

            // Create an object to store provinces
            const province = {};

            // Iterate over each line to extract province code and name
            lines.forEach(line => {
                const parts = line.trim().split(',');
                const provinceCode = parts[2];
                const provinceName = parts[1];

                // Store province code and name in the object
                province[provinceCode] = provinceName;
            });

            const sortedProvince = {};
            Object.keys(province).sort((a, b) => {
                return province[a].localeCompare(province[b]);
            }).forEach(key => {
                sortedProvince[key] = province[key];
            });

            for (const key in sortedProvince) {
                if (sortedProvince.hasOwnProperty(key)) {
                    const val = sortedProvince[key];
                    options += `<option value="${key}">${val}</option>`;
                }
            }

            return options;

        }
    } catch (error) {
        console.error('Error fetching file data:', error);
        return false;
    }
}

//for adding html
async function generate_add_row_html(index, count, url){

    const country_code_options = await create_option_from_file_read(url+'states.db' , 'country_code');
    const province_options = await create_option_from_file_read(url+'municipalities.db' , 'province_options');

    var html=`
    <div class ="table-count" id="table_${index}" data-count="${index}">
    <div class="guest-information-heading guest-info-d-flex align-items-center w-50">
        <div>
            <h3><span class="guest_count">${count}.</span> Guest Information</h3></div>
        <div class="remove-sign" data-id="table_${index}">X</div>
    </div>
    <table class="form-table">

        <tbody>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Name</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="first_name[]" id="first_name${index}"  class=" regular-text" type="text">
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Surname</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="last_name[]" id="last_name${index}" class=" regular-text" type="text">
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Sex</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <select name="sex[]" id="sex${index}">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female" >Female</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Date of Birth</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <input name="dob[]" id="dob${index}"  class=" regular-text" type="date">
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Country of Birth</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <select name="country_code[]" class="ccode" id="country_code${index}" onchange="get_country_name(${index})">
                            <option value="">Select</option>
                            ${country_code_options}
                        </select>
                        <input type="hidden" name="country[]" id="country${index}" value="LESOTHO">
                    </div>
                </td>
            </tr>
            <tr id="provinces${index}" class="dis-none">
                <th>
                    <label for="mphb-mphb_first_name">Province of Birth</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <select name="provinces[]" id="provinces${index}" onchange="get_municipal(${index})">
                            <option value="">Select</option>
                            ${province_options}
                        </select>
                    </div>
                </td>
            </tr>
            <tr id="municipal${index}" class="dis-none">
                <th>
                    <label for="mphb-mphb_first_name">Municipality of Birth</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <select name="municipalities[]" id="municipalities${index}">
                            <option value="">Select</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Guest Type</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <select name="house[]" id="house${index}">
                            <option value="">Select</option>
                            <option value="19">FAMILIARE</option>
                            <option value="20">MEMBRO GRUPPO</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Citizenship</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">
                        <select name="citizenship[]" id="citizenship${index}">
                            <option value="">Select</option>
                            ${country_code_options}
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mphb-mphb_first_name">Upload document front image</label>
                </th>
                <td colspan="1">
                    <div class="mphb-ctrl-wrapper mphb-ctrl mphb-ctrl-text" data-type="text" data-inited="true">

                        <div class="image-preview-container">
                            <input name="doc_img[${index}][]" id="doc_img${index}" class="doc_img regular-text" type="file" accept=".png,.jpg,application/pdf" multiple="">
                            <input name="doc_img_real[]"  type="hidden">
                            <div class="image-preview-div">
                                <div class="image-preview"></div>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    </div>
    `;

    return html;
}
