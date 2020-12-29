<?php
require_once 'head.php';
require_once 'nav.php';
?>
<div>
    <section class="hero is-bold is-danger">
        <div class="hero-body">
            <div class="container columns">
                <h1 class="title column is-8">
                    Monitoring Nodes
                </h1>
            </div>
        </div>
    </section>
    <?php if (empty($nodes)) { ?>
        <div class="section">
            <div class="notification is-danger is-light">
                There are no nodes to monitor right now.
            </div>
        </div>
    <?php } else { ?>
        <div class="section columns is-multiline">
            <?php foreach ($nodes as $node) { ?>
                <div class="column is-4">
                    <div class="card">
                        <header class="card-header has-background-danger-light">
                            <div class="card-header-title">
                                <p class="is-size-3"><?php echo $node->name; ?></p>
                            </div>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <div class="mt-1 px-1">
                                    <p class="is-size-6 has-text-grey mb-0">Last updated</p>
                                    <p class="is-size-6 has-text-weight-medium"><?php echo $node->lastUpdated ?? 'N/A'; ?></p>
                                </div>
                                <div class="mt-1 px-1">
                                    <p class="is-size-6 has-text-grey mb-0">MAC Address</p>
                                    <p class="is-size-6 has-text-weight-medium"><?php echo $node->mac_id; ?></p>
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer has-background-danger-light">
                            <div class="column ml-3">
                                <a class="button is-danger is-rounded" role="button"
                                   href="/monitor/<?php echo $node->mac_id; ?>">
                                    <span>graph</span>
                                </a>
                            </div>
                        </footer>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<?php
require_once 'footer.php';
?>
