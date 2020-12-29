<?php
require_once 'head.php';
require_once 'nav.php';
?>
<div>
    <section class="hero is-danger is-bold">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Create a new node
                </h1>
                <h2 class="subtitle">
                    Monitor with ease
                </h2>
            </div>
        </div>
    </section>

    <section class="section">
        <form class="container" method="post">
            <div class="field">
                <label class="label">What would you like to call this node?</label>
                <div class="control">
                    <label>
                        <input class="input" type="text" placeholder="Drawing Room" name="name"/>
                    </label>
                </div>
            </div>

            <div class="field">
                <label class="label">Enter the physical(MAC) address of your node here</label>
                <div class="control">
                    <label>
                        <input class="input" type="text" placeholder="C8:2B:96:09:0D:56" name="mac_id"/>
                    </label>
                </div>
            </div>

            <div class="field">
                <div class="control has-text-centered">
                    <button class="button is-normal is-danger is-rounded " type="submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>
<?php
require_once 'footer.php';
?>
