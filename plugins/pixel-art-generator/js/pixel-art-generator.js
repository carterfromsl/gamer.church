jQuery(document).ready(function($) {
    $('.pixel-art-generator').each(function() {
        const $container = $(this);
        const $canvas = $container.find('.pixel-canvas');
        const $toolbar = $container.find('.pixel-art-toolbar');
        const width = $container.data('width');
        const height = $container.data('height');
        const scale = $container.data('scale');
        let selectedColor = null;
        let isDrawing = false;
        let undoStack = [];
        let redoStack = [];
        const MAX_UNDO_STEPS = 20;

        // Create canvas grid
        function createCanvas() {
            $canvas.empty();
            $canvas.css({
                'display': 'grid',
                'grid-template-columns': `repeat(${width}, ${scale}px)`,
                'grid-template-rows': `repeat(${height}, ${scale}px)`,
                'width': `${width * scale}px`,
                'height': `${height * scale}px`
            });

            for (let i = 0; i < width * height; i++) {
                const $pixel = $('<div>', {
                    class: 'canvas-pixel',
                    'data-index': i,
                    css: {
                        width: `${scale}px`,
                        height: `${scale}px`,
                        backgroundColor: 'transparent',
                        border: '1px solid #ddd'
                    }
                });
                $canvas.append($pixel);
            }
        }

        // Save canvas state for undo/redo
        function saveCanvasState() {
            const currentState = $canvas.find('.canvas-pixel').map(function() {
                return $(this).css('background-color');
            }).get();

            undoStack.push(currentState);
            
            // Limit undo steps
            if (undoStack.length > MAX_UNDO_STEPS) {
                undoStack.shift();
            }

            // Clear redo stack when a new action is performed
            redoStack = [];
            updateUndoRedoButtons();
        }

        // Restore canvas state
        function restoreCanvasState(state) {
            $canvas.find('.canvas-pixel').each(function(index) {
                $(this).css('background-color', state[index]);
            });
        }

        // Update undo/redo button states
        function updateUndoRedoButtons() {
            $toolbar.find('.undo-canvas').prop('disabled', undoStack.length <= 1);
            $toolbar.find('.redo-canvas').prop('disabled', redoStack.length === 0);
        }

        // Color palette selection
        $toolbar.find('.palette-color').on('click', function() {
            $toolbar.find('.palette-color').removeClass('selected');
            $(this).addClass('selected');
            selectedColor = $(this).data('color');
        });

        // Drawing functionality with mouse events
        function colorPixel($pixel) {
            if (selectedColor) {
                $pixel.css('background-color', selectedColor);
            }
        }

        $canvas.on('mousedown', '.canvas-pixel', function(e) {
            // Prevent default to stop text selection
            e.preventDefault();
            
            if (e.buttons === 1) { // Left mouse button
                isDrawing = true;
                colorPixel($(this));
                saveCanvasState();
            }
        });

        $canvas.on('mousemove', '.canvas-pixel', function(e) {
            if (isDrawing && e.buttons === 1) {
                colorPixel($(this));
            }
        });

        // Stop drawing when mouse leaves canvas or button is released
        $canvas.on('mouseup mouseleave', function() {
            if (isDrawing) {
                isDrawing = false;
            }
        });

        // Touch support for mobile devices
        $canvas.on('touchstart', '.canvas-pixel', function(e) {
            e.preventDefault();
            const touch = e.originalEvent.touches[0];
            const $pixel = $(document.elementFromPoint(touch.clientX, touch.clientY));
            
            if ($pixel.hasClass('canvas-pixel')) {
                colorPixel($pixel);
                saveCanvasState();
            }
        });

        $canvas.on('touchmove', '.canvas-pixel', function(e) {
            e.preventDefault();
            const touch = e.originalEvent.touches[0];
            const $pixel = $(document.elementFromPoint(touch.clientX, touch.clientY));
            
            if ($pixel.hasClass('canvas-pixel')) {
                colorPixel($pixel);
            }
        });

        // Undo functionality
        $toolbar.find('.undo-canvas').on('click', function() {
            if (undoStack.length > 1) {
                // Move current state to redo stack
                redoStack.push(
                    $canvas.find('.canvas-pixel').map(function() {
                        return $(this).css('background-color');
                    }).get()
                );

                // Remove current state from undo stack
                undoStack.pop();

                // Restore previous state
                restoreCanvasState(undoStack[undoStack.length - 1]);
                updateUndoRedoButtons();
            }
        });

        // Redo functionality
        $toolbar.find('.redo-canvas').on('click', function() {
            if (redoStack.length > 0) {
                const stateToRestore = redoStack.pop();
                
                // Add current state to undo stack
                undoStack.push(
                    $canvas.find('.canvas-pixel').map(function() {
                        return $(this).css('background-color');
                    }).get()
                );

                // Restore the state from redo stack
                restoreCanvasState(stateToRestore);
                updateUndoRedoButtons();
            }
        });

        // Clear canvas functionality
        $toolbar.find('.clear-canvas').on('click', function() {
            if (confirm('Are you sure you want to clear the entire canvas?')) {
                saveCanvasState();
                $canvas.find('.canvas-pixel').css('background-color', 'transparent');
                updateUndoRedoButtons();
            }
        });

        // Save canvas as PNG
        $toolbar.find('.save-canvas').on('click', function() {
            html2canvas($canvas[0]).then(canvas => {
                const link = document.createElement('a');
                link.download = 'pixel-art.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        });

        // Initial setup
        createCanvas();
        updateUndoRedoButtons();
    });
});
