<div class="row header">
    <div class="col-lg-12 text-center v-center">
        <h1>String Reverser</h1>
        <h3>Reverse a string of at least 5 characters, to a maximum of 5000 characters.</h3>
    </div>
</div> <!-- /row -->

<div class="">
    <div>
        <label>Enter a string<span class="required">*</span></label>
        <button class="secondary" onclick="clear_area()">Clear</button>
        <textarea id="input_string"></textarea>
        <p id="string_count"></p>
    </div>
    <div class="control_buttons">
        <button class="primary" onclick="generate_reverse()">Reverse</button>    
    </div>
</div>
<div id="generator_results"></div>
<script type="text/javascript">
    var string_length = 0;
    var input_string_textarea = document.getElementById("input_string");
    var string_length_div = document.getElementById("string_count");
    var generator_results_div = document.getElementById("generator_results");

    input_string_textarea.onpaste = function(){check_length};

    input_string_textarea.addEventListener('keyup', check_length);
    
    function check_length() {
        string_length = this.value.toString().length;
        string_length_div.innerHTML = 'Entered '+string_length+'/5000 characters.';
        
        if ( string_length < 5 || string_length > 5000 ) {
            string_length_div.style.color = '#900';
        } else {
            string_length_div.style.color = '#000';
        }
    }

    function get_errors() {
        input_string_textarea.classList.remove("error");

        var errors = [];
        if ( string_length < 5 ) {
            errors.push('The string entered is too short. Enter more '+(5-string_length)+' character'+( string_length < 4 ? 's' : '' )+'.');
        } else if ( string_length > 5000 ) {
            errors.push('The string entered is too long.');
        }

        return errors;
    }

    function generate_reverse() {

        generator_results_div.innerHTML = '';

        var input_string = input_string_textarea.value.toString();

        var errors = get_errors();

        if ( errors.length > 0 ) {
            input_string_textarea.classList.add("error");
            generator_results_div.innerHTML = '<div class="failure">'+errors.join("<br/>")+'</div>';
        } else {
            var new_string = '';
            var i = string_length;
            do {
                i--;
                new_string += '<span>'+input_string[i]+'</span>';
            } while ( i > 0 );
    
            generator_results_div.innerHTML = '<div class="success"><label>Your reversed string:</label><div>'+new_string+'</div></div>';
        }
    }

    function clear_area() {
        input_string_textarea.value = '';
        input_string_textarea.classList.remove("error");
        generator_results_div.innerHTML = '';
        string_length_div.innerHTML = '';
        string_length = 0;
    }
</script>
