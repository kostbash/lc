<?php Yii::app()->clientScript->registerScriptFile(
    Yii::app()->assetManager->publish(
        Yii::getPathOfAlias('ext.visJs') . "/cytoscape.min.js"
    )
); ?>

<div id="cy" style="width: 100%; height: 900px"></div>
<script type="text/javascript">
    $(function(){ // on dom ready

        $('#cy').cytoscape({
            style: cytoscape.stylesheet()
                .selector('node')
                .css({
                    'content': 'data(name)',
                    'text-valign': 'center',
                    'color': 'white',
                    'shape': 'rectangle',
                    'width': "data(width)",
                    'height': '50'
                })
                .selector('edge')
                .css({
                    'target-arrow-shape': 'triangle',
                    'width': '1',
                    'line-color': '#333',
                    'target-arrow-color': '#000'
                })
                .selector(':selected')
                .css({
                    'background-color': 'black',
                    'line-color': 'black',
                    'target-arrow-color': 'black',
                    'source-arrow-color': 'black'
                })
                .selector('.faded')
                .css({
                    'opacity': 0.5,
                    'text-opacity': 1
                }),
            elements: {
                nodes: [
                    <?=$nodes?>
                ],
                edges: [
                    <?=$edges?>
                ]
            },

            layout: {
                name: 'breadthfirst',

            },

            // on graph initial layout done (could be async depending on layout...)
            ready: function(){
                window.cy = this;

                // giddy up...

                cy.elements().unselectify();

                cy.on('tap', 'node', function(e){
                    var node = e.cyTarget;
                    var neighborhood = node.neighborhood().add(node);

                    cy.elements().addClass('faded');
                    neighborhood.removeClass('faded');
                });

                cy.on('tap', function(e){
                    if( e.cyTarget === cy ){
                        cy.elements().removeClass('faded');
                    }
                });

                cy.on('mouseover', 'node', function(e){

                    var id = e.cyTarget._private.data.id;
                    $.ajax({
                        url: "/courses/skillbyajax/"+id,
                        cache: true,
                        success: function(html){
                            if (html) {
                                $("#skillPanel").html(html);
                            }
                            $('#skillPanel').css('left', e.cyRenderedPosition.x).css('top', e.cyRenderedPosition.y + 170).show();
                        }
                    });
                });
                cy.on('mouseout', 'node', function(e){
                    $('#skillPanel').hide();
                    $("#skillPanel").html('Описание отсутствует');

                });
            }
        });

    }); // on dom ready
</script>

<div class="panel" id="skillPanel" style="display: none; padding: 10px 20px; position: absolute; z-index: 100000000">
    Описание отсутствует
</div>
