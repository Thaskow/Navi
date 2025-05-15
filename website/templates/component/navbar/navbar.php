
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // hover wait 1s before showing the hover-text
        $(".icone").hover(function() {
            $(this).find(".hover-text").show();
        }, function() {
            $(this).find(".hover-text").hide();
        });
    });
</script>

<style>
    html {
        background: #f6f6f6;
        font-family: 'Montserrat', sans-serif;
    }
    .navbar {
        padding-top: 1rem;
        background: #ffffff;
        position: absolute;
        top: 0;
        left: 0;
        width: 7rem;
        border-right: solid 1px #e5e4e4;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;
    }
    .navbar .top-nav, .navbar .bottom-nav {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .navbar .bottom-nav {
        margin-bottom: 2rem;
    }
    .navbar img {
        border-radius: 1rem;
        width: 100%;
        height: 100%;
    }
    .navbar a {
        margin: .5rem 0;
        border-radius: 1rem;
        width: 55%;
        border: solid 1px #e5e4e4;
    }
    .navbar .icone {
        position: relative;
    }
    .navbar .hover-text {
        font-size: 0.8rem;
        font-weight: bold;
        display: none;
        position: absolute;
        background: #141414;
        left: 120%;
        top: 15%;
        padding: .5rem 0.8rem;
        color: white;
        border-radius: 0.5rem;
    }
</style>

<div class="navbar">
    <div class="top-nav">
    <a href="https://navi.signauxgirod.com/">
        <div class="icone">
            <div class="hover-text">Switch</div>
            <div class="logo">
                <div class="background-color">
                    <img src="../../../data/assets/switch.jpg" alt="">
                </div>
            </div>
        </div>
    </a>
    <a href="https://navi.signauxgirod.com/warning.php">
        <div class="icone">
            <div class="hover-text">Warning</div>
            <div class="logo">
                <div class="background-color">
                    <img src="../../../data/assets/warning.png" alt="">
                </div>
            </div>
        </div>
    </a>
    <a href="https://navi.signauxgirod.com/whitelist.php">
        <div class="icone">
            <div class="hover-text">Whitelist</div>
            <div class="logo">
                <div class="background-color">
                    <img src="../../../data/assets/wl.png" alt="">
                </div>
            </div>
        </div>
    </a>
</div>
<div class="bottom-nav">
    <a href="https://navi.signauxgirod.com/settings.php">
        <div class="icone">
            <div class="hover-text">Configuration</div>
            <div class="logo">
                <div class="background-color">
                    <img src="../../../data/assets/setting.jpg" alt="">
                </div>
            </div>
        </div>
    </a>
</div>
</div>