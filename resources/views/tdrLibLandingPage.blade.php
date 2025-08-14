<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheDirectorsRoom.com Choral Library Application</title>
    <style>
        html {
            scroll-padding-top: 200px;
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        #headerNav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        #content {
            margin-top: 200px;
            width: 100%;

        }

        footer {
            background-color: #003366;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        footer a {
            color: cornsilk;
        }
        header {
            background-color: #003366;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        img {
            max-height: 400px;
            max-width: 50%;
            margin-left: 25%;
        }

        nav {
            background-color: #005599;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            padding: 0.5rem 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        section {
            padding: 2rem;
            max-width: 900px;
            margin: auto;
            background-color: white;
            margin-bottom: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #003366;
        }

        img {
            width: 100%;
            height: auto;
            margin-top: 1rem;
            border-radius: 4px;
        }

        /* w3.schools.com/howto/howto_js_slideshow.asp */
        * {
            box-sizing: border-box
        }

        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
        }

        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -22px;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: darkgray; /*#f2f2f2; */
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active, .dot:hover {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {
                opacity: .4
            }
            to {
                opacity: 1
            }
        }
    </style>
</head>
<body>
<div id="headerNav">
    <header>
        <h1>TheDirectorsRoom.com</h1>
        <h2 style="color: white;">Choral Library Application</h2>
    </header>
    <nav style="display: flex; flex-direction: row; justify-content: start;">
        <div style="display: flex; width: 25%; margin: 0 1rem;">
            <a href="/" title="return to TheDirectorsRoom.com">Back</a>
        </div>
        <div style=" display:flex; flex-direction: row; width: 45%; justify-content: center; ">
            <a href="#overview" style="margin-right: 3rem;">Overview</a>
            <a href="#keyFeatures" style="margin-right: 3rem;">Key Features</a>
            <a href="#samples" style="margin-right: 3rem;">Samples</a>
            <a href="#pricing" style="margin-right: 3rem;">Pricing</a>
        </div>
    </nav>
</div>

<div id="content">

    <section id="overview">
        <h2>Overview</h2>
        {{-- v2 --}}
        <p>Introducing the Choral Library Application!</p>
        <p>This application will allow you to maintain a comprehensive record of your choral library including
            octavos, medleys, books, DVDs, CDs, cassettes, and vinyl recordings in a simple, easy-to-use
            format that you can access anytime from anywhere.</p>
        <p>Features include fields such as title, composer, arranger, voicing, price, tags (your personal search words),
            levels (high school/middle school/elementary school/college/professional),
            difficulty (hard/medium/easy),comments, rating, location in your library, and performance history.</p>
        {{-- v1 --}}
        {{--        <p>TheDirectorsRoom.com introduces the Library Application, built specifically for choral directors. This application --}}
        {{--            allows you--}}
        {{--            to maintain an on-demand record of your choral library including octavos, medleys, books, DVDs, CDs,--}}
        {{--            cassettes, and vinyl recordings—all in a simple, easy-to-use format you can access anytime from--}}
        {{--            anywhere.</p>--}}
        {{--        <p>Features include fields such as title, composer, arranger, voicing, price, tags, levels, difficulty,--}}
        {{--            comments,--}}
        {{--            rating, physical location, and performance history with many of those fields immediately searchable.</p>--}}
        <p>Upload your existing library via CSV file, enter information manually, or delegate the data entry
            to a student librarian using secure limited-access credentials.</p>
        <p>Filter and sort by various criteria. Create “pull sheets” for easy octavo retrieval. Easily link ensemble
            members and performances via the Programs, Ensembles, and Students applications.</p>
        <p>Easily transition your present choral library from paper or spreadsheets to a searchable,
            sortable, and powerful database.</p>
    </section>

    <section id="keyFeatures">
        <h2>Key Features</h2>
        <ul>
            <li>Search by titles, composers, arrangers, voicing, tags, and more.</li>
            <li>Continue to use your own library location codes, or use our numbers to file your music.</li>
            <li>Upload existing library via CSV or manual entry.</li>
            <li>Safe and secure student librarian login.</li>
            <li>Create library item pull sheets which include physical storage location.</li>
            <li>The Choral Library automatically pairs with TheDirectorsRoom.com Programs, Ensembles, and Students
                applications.
            </li>
        </ul>
    </section>

    <section id="andThenWhat">
        <h2>...and then what?</h2>
        <div>
            <p>When your library is in a database, your ability to use it multiplies!</p>
            <ul>
                <li>Using the “Program” application, you can create a record of your performances including title,
                    composer, arranger, performance notes, and ratings.
                </li>
                <li>Using the "Students" application, create rosters of your students by school year.</li>
                <li>Using the “Ensembles” application, create lists of participating student members by year of
                    performance.
                </li>
            </ul>
            <p>And all of this is searchable, of course. Can't remember the last time you conducted "O Magnum
                Mysterium"?
                Just pop <b><i>O Magnum</i></b> into the search bar of the Library application or
                <b><i>"O Magnum"</i></b> (with double-quotes) into the Programs application and you'll have your answer
                ...
                in seconds!</p>
        </div>
    </section>

    {{-- https://www.w3schools.com/howto/howto_js_slideshow.asp --}}
    <!-- Slideshow container -->
    <div id="samples" class="slideshow-container">

        <!-- Full-width images with number and caption text -->
        <div class="mySlides fade">
            <div class="numbertext">1 / 4 Sample Library</div>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_library.png') }}"
                 style="width:100%"
                 alt="Sample Library Table"
            >
            <div class="text">Sample Library</div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">2 / 4 Sample Medley</div>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_medley.png') }}"
                 style="width:100%"
                 alt="Sample Library Table"
            >
            <div class="text">Sample Medley</div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">3 / 4 Sample Pull Sheet</div>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_pullSheet.png') }}"
                 style="width:100%"
                 alt="Sample Pull Sheet"
            >
            <div class="text">Sample Pull Sheet</div>
        </div>

        <div class="mySlides fade">
            <div class="numbertext">4 / 4 Sample Program</div>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_program.png') }}"
                 style="width:100%"
                 alt="Sample Program"
            >
            <div class="text">Sample Program</div>
        </div>

        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    <br>

    <!-- The dots/circles -->
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)" title="sample library"></span>
        <span class="dot" onclick="currentSlide(2)" title="sample medley"></span>
        <span class="dot" onclick="currentSlide(3)" title="sample pull sheet"></span>
        <span class="dot" onclick="currentSlide(4)" title="sample program"></span>
    </div>

    <section id="pricing">
        <h2>Subscription Plans</h2>
        <div id="rationale">
            {{-- v2 --}}
            <p>The Choral Library is the result of years of planning and hundreds of
                programming hours -- bringing you a powerful, all-in-one resource to
                organize and access your music like never before.</p>
            <p>Please show your support by subscribing to one of the following plans:</p>
            {{-- v1 --}}
            {{--            <p>These features have taken years of planning and discussion, and required hundreds of hours of--}}
            {{--                programming.--}}
            {{--                From the start, the goal has been to provide you with a tool that could minimally reduce your workload--}}
            {{--                by an hour/month.</p>--}}
            {{--            <p>You'll be the final judge, but we think we’ve done that and hope to continue to build and maintain--}}
            {{--                this effort.<br/>--}}
            {{--                To do that, we are asking for your support by subscribing to one of our plans:</p>--}}
        </div>
        <style>
            table.pricing-table {
                border-collapse: collapse;
            }

            .pricing-table td, th {
                border: 1px solid slategray;
                padding: 0 0.25rem;
            }

            .pricing-table .odd {
                background-color: aliceblue;
            }
        </style>
        <table class="pricing-table">
            <thead>
            <tr style="background-color: darkslategray; color: white;">
                <th>Plan</th>
                <th>Applications<sup>**</sup></th>
                <th>Monthly</th>
                <th>Yearly</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr class="odd">
                <td>Pioneer<span style="color: red;">*</span></td>
                <td style="text-align: center;">7</td>
                <td style="text-align: right; padding-right: 1rem;">$2.99</td>
                <td style="text-align: right; padding-right: 1rem;">$27</td>
                <td>Our earliest supporters and beta testers, instrumental in shaping the platform.</td>
            </tr>
            <tr>
                <td>Early Adopter<span style="color: red;">*</span></td>
                <td style="text-align: center;">7</td>
                <td style="text-align: right; padding-right: 1rem;">$4.99</td>
                <td style="text-align: right; padding-right: 1rem;">$45</td>
                <td>Sign up at the launch or be among the first subscribers beyond New Jersey.</td>
            </tr>
            <tr class="odd">
                <td>Patron<span style="color: red;">*</span></td>
                <td style="text-align: center;">7</td>
                <td style="text-align: right; padding-right: 1rem;">$6.99</td>
                <td style="text-align: right; padding-right: 1rem;">$63</td>
                <td>Wait for the product to evolve and then join to enjoy the full benefits.</td>
            </tr>
            <tr>
                <td>Sponsor<span style="color: red;">*</span></td>
                <td style="text-align: center;">7</td>
                <td style="text-align: right; padding-right: 1rem;">$10.99</td>
                <td style="text-align: right; padding-right: 1rem;">$99</td>
                <td>I'm here to help shape the future of the product direction and champion the choral community.</td>
            </tr>
            <tr class="odd">
                <td>Enterprise (School)</td>
                <td style="text-align: center;">7</td>
                <td style="text-align: center">–</td>
                <td style="text-align: right; padding-right: 1rem;">$399</td>
                <td>School-wide license through PO.</td>
            </tr>
            <tr>
                <td>Enterprise (District)</td>
                <td style="text-align: center;">7</td>
                <td style="text-align: center">–</td>
                <td style="text-align: right; padding-right: 1rem;">$799</td>
                <td>District-wide license through PO.</td>
            </tr>
            <tr class="odd">
                <td>Teacher</td>
                <td>
                    5
                    <span style="font-size: 0.66rem; font-style: italic;">(excludes Ensembles and Programs</span>
                </td>
                <td style="text-align: right; padding-right: 1rem;">$2.99</td>
                <td style="text-align: right; padding-right: 1rem;">$27</td>
                <td>For now, I only want to use the Library application.</td>
            </tr>
            </tbody>
        </table>
        <p>
            <span>**</span> Applications: Schools, Students, Ensembles, Libraries, Programs, Events, Profile
        </p>

        <p><strong><span style="color: red;">*</span>Community Contribution:</strong>
            Individual subscriptions
            (other than Teacher) provide access to the full Library-Ensemble-Program applications' features and
            benefits. These plans <b><u>include your commitment</u></b> to add a minimum of two programs per year.
            This commitment will ensure the growth of the Library and provide relevant trending insights to your
            community's ensembles.</p>

        <div
            style="border: 1px solid darkblue; background-color: cornsilk; padding: 0.5rem; border-radius: 10px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3)">
            <h2>SPECIAL NOTICE</h2>

            <p>Our subscription payment link will become available in January, 2026.
            <p>

            <p>Until then, <u>all of the features are available</u> for your use and comment!</p>

            <p>NOTE: All teachers who use the Library application and add two new programs will be
                immediately eligible for the "Early Adopter" tier in January!
            </p>
        </div>
    </section>
</div><!-- end of id=content -->

<footer>
    Questions/Comments? Email:
    <a
        href="mailTo: rick@mfrholdings.com?subject=TDR Library&body=Hi, Rick!"
    >
        rick@mfrholdings.com
    </a>
</footer>

<script>
    let slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }

</script>


</body>
</html>
