<style>
    #footer-container {
        background-color: white;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        bottom: 0;
        font-size: 0.8rem;
        left: 0;
        margin-top: 1rem;
        padding-bottom: 0.25rem;
        position: fixed;
        width: 100%;
        z-index: -1;
    }

    #footer-container #content-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        margin: 0 1rem;
    }
</style>
<div id="footer-container">
    <div id="content-container">

        <div>
            &copy {{ date('Y') }}
        </div>

        <div class="flex flex-row">
            powered by:
            <a href="https://mfrholdings.com">
                MFR Holdings, LLC
                <span style="margin-left: 0.5rem; font-size: smaller;"> v.2024.05.07</span>
            </a>

        </div>
        <div>
            <a href="mailto:rick@mfrholdings.com">
                Contact Us
            </a>
        </div>
    </div>
</div>
