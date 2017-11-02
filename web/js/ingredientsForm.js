const actionsSelect = document.getElementById('ingredient_action'),
      results = document.getElementsByClassName('submit_results').item(0).dataset.results,

      showCurrentStep = function(hashStep) {
          let hashStepSplitted = hashStep.split('-');

          $('.form-step').addClass('hidden');

          hashStepSplitted.forEach(step => {
              $('.form-step#' + step).removeClass('hidden');
          });
      },

      onHashChange = function(ev) {
          let hashStep = document.location.hash.substr(1);

          ev.preventDefault();

          if (this.current_step !== hashStep) {
              showCurrentStep(hashStep);
          }
      },

      updateHash = function() {
          document.location.hash = '#' + current_step;
      },

      modifyStep = function(action) {
          current_step = action;
          updateHash();
      },

      init = function() {
          let current_step = document.location.hash.substr(1) || 'list';
          
          $('#ingredient_action').val(current_step).change();
          showCurrentStep(current_step);

          $(window).bind('hashchange', onHashChange.bind(this));

          actionsSelect.addEventListener("change", function() {
              if(actionsSelect.value) {
                  modifyStep(actionsSelect.value)
              }
          });
          
          if (results) {
              let html = results.split(';').map(result => {
                  return '<div class="result-item">' + result + '</div>';
              }).join('');

              $('.submit_results').html(html);
          } 
      };

let current_step = 'list';

window.onload = init;