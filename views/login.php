<?php
require_once 'head.php';
?>
<style>
    :root {
        --brandColor: hsl(31, 92%, 40%);
        --background: rgb(247, 247, 247);
        --textDark: hsla(0, 0%, 0%, 0.66);
        --textLight: hsla(0, 0%, 0%, 0.33);
    }

    body {
        background: var(--background);
        height: 100vh;
        color: var(--textDark);
    }

    .field:not(:last-child) {
        margin-bottom: 1rem;
    }

    .register {
        margin-top: 5rem;
        background: white;
        border-radius: 10px;
    }

    .left,
    .right {
        padding: 4.5rem;
    }

    .left {
        border-right: 5px solid var(--background);
    }

    .left .title {
        font-weight: 800;
        letter-spacing: -2px;
    }

    .left .colored {
        color: var(--brandColor);
        font-weight: 500;
        margin-top: 1rem !important;
        letter-spacing: -1px;
    }

    .left p {
        color: var(--textLight);
        font-size: 1.15rem;
    }

    .right .title {
        font-weight: 800;
        letter-spacing: -1px;
    }

    .right .description {
        margin-top: 1rem;
        margin-bottom: 1rem !important;
        color: var(--textLight);
        font-size: 1.15rem;
    }

    .right small {
        color: var(--textLight);
    }

    input {
        font-size: 1rem;
    }

    input:focus {
        border-color: var(--brandColor) !important;
        box-shadow: 0 0 0 1px var(--brandColor) !important;
    }

</style>
<section class="container py-4">
    <div class="columns is-multiline">
        <div class="column is-8 is-offset-2 register">
            <div class="columns">
                <div class="column left">
                    <h1 class="title is-1">Noise Monitoring System</h1>
                    <h2 class="subtitle colored is-4">Get hold of the decibels.</h2>
                    <p>Be the first to know of a noise issue at your place and protect against damage.</p>
                </div>
                <div class="column right has-text-centered">
                    <h1 class="title is-4">Sign in now</h1>
                    <p class="description">Peace of mind at your fingertips</p>
                    <?php if (isset($error)) { ?>
                        <div class="notification is-danger is-light">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <form method="post">
                        <div class="field">
                            <div class="control">
                                <label>
                                    <input name="username" class="input is-medium" type="text" placeholder="Username">
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <label>
                                    <input name="password" class="input is-medium" type="password"
                                           placeholder="Password">
                                </label>
                            </div>
                        </div>
                        <button class="button is-block is-info is-fullwidth is-medium">Login</button>
                        <br/>
                        <small>&copy; Noise Monitoring System | <?php echo date('Y'); ?></small>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'footer.php'; ?>
