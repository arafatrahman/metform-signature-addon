jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/signature_widget.default', function ($scope) {
        const canvas = $scope.find('#mf-signature-canvas')[0];
        
        if (canvas) { // Check if the canvas element exists
            // Set canvas width and height to match its display size
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            const signaturePad = new SignaturePad(canvas);
            const clearButton = $scope.find('#mf-clear-signature')[0];
            const saveButton = $scope.find('#mf-save-signature')[0];

            const signatureDataInput = $scope.find('#mf-signature-data')[0];

            console.log(signatureDataInput);

            // Update hidden input field with the current signature data
            function updateSignatureData() {
                if (signaturePad.isEmpty()) {
                    signatureDataInput.value = ''; // Clear data if signature is empty
                } else {
                    signatureDataInput.value = signaturePad.toDataURL(); // Set data URL
                }
            }

            // Handle the 'clear' button click
            clearButton.addEventListener('click', function () {
                signaturePad.clear();
                signatureDataInput.value = ''; // Clear hidden field when cleared
            });

            // Handle the 'save' button click
            saveButton.addEventListener('click', function () {
                updateSignatureData();
            });

            // Recalculate canvas size on window resize
            window.addEventListener('resize', function () {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                signaturePad.clear();  // Clears previous drawings to prevent distortion
            });
        } else {
            console.error('Signature canvas element not found');
        }
    });
});
