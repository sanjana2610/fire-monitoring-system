<?php
require_once 'head.php';
require_once 'nav.php';

use Carbon\Carbon;

?>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script type="text/javascript">
    window.onload = function () {
        $(function () {
            $("#chartContainer").CanvasJSChart({
                animationEnabled: true,
                title: {
                    text: "Decibel Levels"
                },
                axisX:{
                    title: "Time"
                },
                axisY: {
                    title: "Sound Level",
                    suffix: ' dB'
                },
                data: [
                    {
                        type: "splineArea",
                        dataPoints: [
                            <?php if (!empty($data)) foreach ($data as $datum) { ?>
                            <?php
                            echo "{x: new Date(" .
                                Carbon::createFromFormat(
                                    "Y-m-d H:i:s",
                                    $datum->created_at,
                                    'Asia/Kolkata'
                                )->timestamp .
                                " * 1000), y: $datum->sound},\n";
                            ?>
                            <?php } ?>
                        ]
                    }
                ]
            });

        });
    }
</script>
<div>
    <section class="hero is-bold is-info mb-2">
        <div class="hero-body">
            <div class="container columns">
                <h1 class="title column is-8">
                    <?php echo $node_name ?? ''; ?>
                </h1>
            </div>
        </div>
    </section>
    <div class="container" id="chartContainer"></div>
</div>
<?php
require_once 'footer.php';
?>
