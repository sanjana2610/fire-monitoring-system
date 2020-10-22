<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="/">
            <img
                    class="mr-1"
                    src="../images/logo.svg"
                    width="30"
                    height="28"
                    alt="Website logo"
            />
            <h1 class="subtitle">Noise Monitoring System</h1>
        </a>

        <a
                role="button"
                class="navbar-burger burger"
                aria-label="menu"
                aria-expanded="false"
                data-target="navbarBasicExample"
        >
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-end mr-2">
            <a class="navbar-item" href="/new-node">
                <svg
                        width="1em"
                        height="1em"
                        viewBox="0 0 16 16"
                        class="bi bi-plus-circle-fill mr-1"
                        fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                            fill-rule="evenodd"
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4a.5.5 0 0 0-1 0v3.5H4a.5.5 0 0 0 0 1h3.5V12a.5.5 0 0 0 1 0V8.5H12a.5.5 0 0 0 0-1H8.5V4z"
                    />
                </svg>
                Add a new node
            </a>
            <a class="navbar-item" href="#" role="button" onclick="Swal.fire('Here\'s your API key','<?php echo $api_key ?? 'N/A';  ?>','info')">
                <svg width="1.3em" height="1.3em" viewBox="0 0 16 16" class="bi bi-key-fill mr-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
                API Key
            </a>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    <svg
                            width="1em"
                            height="1em"
                            viewBox="0 0 16 16"
                            class="bi bi-person-circle mr-2"
                            fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                                d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"
                        />
                        <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path
                                fill-rule="evenodd"
                                d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"
                        />
                    </svg>
                    Good&nbsp;<span id="greeting"></span>, <?php echo $username; ?>
                    <script>
                        const hours = new Date().getHours();
                        let greeting;

                        if (hours >= 5 && hours <= 11) greeting = "morning";
                        else if (hours === 12) greeting = "noon";
                        else if (hours >= 13 && hours <= 17) greeting = "afternoon";
                        else if (hours >= 18 && hours <= 20) greeting = "evening";
                        else greeting = "night";

                        document.getElementById('greeting').innerText = greeting;
                    </script>
                </a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="/logout">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
