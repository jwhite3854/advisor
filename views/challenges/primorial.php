<style>
    h3 { font-size: 22px; }
    p { font-size: 16px; }
    .input_fields { background-color: #fff; border-radius: 2px; box-shadow: 0 1px 2px #ccd; }
    label { font-size: 14px; margin: 0 4px; }
    input { width: 100%; text-align: right; font-size: 18px; padding: 8px; border: 0; background-color: #fff; border-bottom: 2px #3b5998 solid; }
        
    input:focus { border-bottom: 2px #0f0 solid; }

    .required > span { color: #900; }
    .control_buttons { margin: 12px 0; text-align: right; padding: 12px 0; }
    .control_buttons button { height: 40px; background-color: #fff; font-size: 0.9em; border: 1px #ccd solid; padding: 6px 16px; border-radius: 4px; color: #193776; box-shadow: 0 2px 4px #ccd; cursor: pointer; } 
    .control_buttons button.primary { color: #edeeff; background-color: #193776 }
    .control_buttons button.secondary { float: unset; }
    .control_buttons button:hover { background-color: #4e69a2; color: #edeeff }
    
    .input_fields.error { background-color: #fee; }
    .error > input { background-color: #fee; border-bottom: 2px #900 solid; }

    #generator_results > div { margin: 12px 0 0; padding: 16px 8px; border: 1px #ccd solid; background-color: #fff; border-radius: 3px; box-shadow: 0 2px 4px #ccd; line-height: 1.45em; }
    #generator_results .failure { border: 1px #900 solid; color: #900; }

</style>

<div class="row header">
    <div class="col-lg-12 text-center v-center">
        <h1>Primorial Generator</h1>
        <h3>Generate a list of prime numbers between given values, to a maximum of 10,000.</h3>
        <p>General consensus appears to be negative numbers aren't prime. Neither are 0 or 1.</p>
    </div>
</div> <!-- /row -->

<div class="row">
    <div class="col-md-4">
        <div class="input_fields">
            <label class="required">Minimum<span>*</span></label>
            <input type="text" id="min" value="0" />
        </div>
    </div>
    <div class="col-md-4">
        <div class="input_fields">
            <label class="required">Maximum<span>*</span></label>
            <input type="text" id="max" value="100"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="control_buttons">
            <button class="secondary" onclick="reset_values()">Reset</button>    
            <button class="primary" onclick="generate_list()">Generate</button>    
        </div>
    </div>
</div> <!-- /row -->
<div id="generator_results"></div>

<script type="text/javascript">
    var min = document.getElementById("min");
    var max = document.getElementById("max");
    var generator_results = document.getElementById("generator_results");

    function generate_list() {
        
        generator_results.innerHTML = '';
        min.parentNode.classList.remove("error");
        max.parentNode.classList.remove("error");

        var x = parseInt(min.value);
        var y = parseInt(max.value);

        var errors = [];

        if ( min.value != x || !Number.isSafeInteger(x) ) {
            min.parentNode.classList.add("error");
            errors.push('The minimum value is not a valid integer.');
        } else if ( x < 0 ) {
            min.parentNode.classList.add("error");
            errors.push('Enter a positive integer.');
        }

        if ( max.value != y || !Number.isSafeInteger(y) ) {
            max.parentNode.classList.add("error");
            errors.push('The maximum value is not a valid integer.');
        } else if ( y > 10000 ) {
            max.classList.add("error");
            errors.push('Enter a maximum value less than or equal to 10000.');
        }

        if ( x >= y ) {
            min.parentNode.classList.add("error");
            max.parentNode.classList.add("error");
            errors.push('The Minimum Value is not less than the Maximum Value');
        }

        if ( errors.length > 0 ) {
            generator_results.innerHTML = '<div class="failure">'+errors.join("<br/>")+'</div>';
        } else {
            var list = [];
            while ( x <= y ) {
                if ( is_prime(x) ) {
                    list.push(x);
                }
                x++;
            }
    
            generator_results.innerHTML = '<div class="success">'+list.join(", ")+'</div>';
        }
    }

    function is_prime(n) {

        if ( n < 2 ) {
            return;
        }
        
        var i = 2;
        while ( i < n ) {
            if ( n % i == 0 ) {
                return;
            }
            i++;
        }
        
        return true;
    }

    function reset_values() {
        min.value = 0;
        min.parentNode.classList.remove("error");
        max.value = 100;
        max.parentNode.classList.remove("error");
        generator_results.innerHTML = '';
    }

</script>