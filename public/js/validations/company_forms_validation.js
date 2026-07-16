/*
 *  Document   : be_forms_validation.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Forms Validation Page
 */

// Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
class companyFormsValidation {
  /*
   * Init Validation functionality
   *
   */
  static initValidation() {
    // Load default options for jQuery Validation plugin
    One.helpers("jq-validation");

    // Init Form Validation
    $(".js-validation").validate({
      ignore: [],
      rules: {
        "name-eng": {
          required: true,
          minlength: 3,
        },
        "name-ur": {
          required: true,
          emailWithDot: true,
        },
      },
      messages: {
        "name-eng": {
          required: "Please enter a name in english",
        },
        "name-ur": {
          required: "Please enter a name in urdu",
        },
    }
    });

    // Init Validation on Select2 change
    jQuery(".js-select2").on("change", (e) => {
      jQuery(e.currentTarget).valid();
    });
  }

  /*
   * Init functionality
   *
   */
  static init() {
    this.initValidation();
  }
}

companyFormsValidation.init();

// Initialize when page loads
One.onLoad(() => companyFormsValidation.init());
