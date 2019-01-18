<div class="navBar">
    <div class="navBarContent">

        <a href="/" >
            <div class="navBarItem">
                <i class="material-icons">list</i> Trips
            </div>
        </a>
        <?php if (isset($_SESSION["Username"])): ?>
            <a href="/session/logout" >
                <div class="navBarItem navBarItemRight">

                    <?=$_SESSION["Username"]?> <i class="material-icons">logout</i>

                </div>
            </a>
        <?php endif; ?>

    </div>
</div>