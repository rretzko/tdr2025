<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheDirectorsRoom.com Library Module</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 1rem;
            text-align: center;
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
            color: #f2f2f2;
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
<header>
    <h1>TheDirectorsRoom.com Library Module</h1>
    <p>Your Choral Library, Fully Organized and Always Available</p>
</header>
<nav>
    <a href="#overview">Overview</a>
    <a href="#keyFeatures">Key Features</a>
    <a href="#samples">Samples</a>
    php <a href="#pricing">Pricing</a>
</nav>

<section id="overview">
    <h2>Overview</h2>
    <p>TheDirectorsRoom.com introduces the Library Module, built specifically for choral directors. This tool allows you
        to maintain an on-demand record of your choral library including sheet music, medleys, books, DVDs, CDs,
        cassettes, and vinyl recordings—all in a clean, always-accessible web interface.</p>
    <p>Features include searchable fields such as title, artist, voicing, price, tags, levels, difficulty, comments,
        rating, physical location, and performance history. Upload via CSV or enter manually. Hand off entry to a
        student librarian using secure limited-access credentials.</p>
    <p>Filter and sort by various criteria and create “pull sheets” for easy item retrieval. Easily link to ensemble
        members and performances via the Programs, Ensembles, and Students modules. Subscription plans available with
        tiered benefits and optional community contributions.</p>
    <p>This system is designed to transition you from paper or spreadsheet-based systems to a searchable, sortable, and
        powerful database-driven interface.</p>
</section>

<section id="keyFeatures">
    <h2>Key Features</h2>
    <ul>
        <li>Track titles, artists (composer, arranger, choreographer, etc.), voicing, tags, difficulty, ratings,
            comments, and more
        </li>
        <li>Three-part location system for detailed item storage</li>
        <li>Upload existing library via CSV or enter manually</li>
        <li>Student librarian login for safe delegation</li>
        <li>Search by title, tag, artist; filter by voicing</li>
        <li>Create performance pull sheets</li>
        <li>Use in conjunction with Program, Ensemble, and Student modules</li>
    </ul>
</section>

{{-- https://www.w3schools.com/howto/howto_js_slideshow.asp --}}
<!-- Slideshow container -->
<div id="samples" class="slideshow-container">

    <!-- Full-width images with number and caption text -->
    <div class="mySlides fade">
        <div class="numbertext">1 / 4</div>
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_library.png') }}"
             style="width:100%"
             alt="Sample Library Table"
        >
        <div class="text">Sample Library</div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">2 / 4</div>
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_medley.png') }}"
             style="width:100%"
             alt="Sample Library Table"
        >
        <div class="text">Sample Medley</div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">3 / 4</div>
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_pullSheet.png') }}"
             style="width:100%"
             alt="Sample Pull Sheet"
        >
        <div class="text">Sample Pull Sheet</div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">4 / 4</div>
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
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
    <span class="dot" onclick="currentSlide(4)"></span>
</div>

{{--<div class="carousel-container">--}}
{{--    <div class="carousel-slide" id="carouselSlide">--}}
{{--        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_library.png') }}"--}}
{{--             alt="Sample Library Table"--}}
{{--        >--}}
{{--        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_medley.png') }}"--}}
{{--             alt="Sample Medley Entry Form"--}}
{{--        >--}}
{{--        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_pullSheet.png') }}"--}}
{{--             alt="Sample Pull Sheet"--}}
{{--        >--}}
{{--        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_program.png') }}"--}}
{{--             alt="Sample Concert Program"--}}
{{--        >--}}
{{--    </div>--}}
{{--    <div class="carousel-buttons">--}}
{{--        <button onclick="prevSlide()">❮</button>--}}
{{--        <button onclick="nextSlide()">❯</button>--}}
{{--    </div>--}}
{{--</div>--}}

<section id="pricing">
    <h2>Subscription Plans</h2>
    <table class="pricing-table">
        <thead>
        <tr>
            <th>Plan</th>
            <th>Monthly</th>
            <th>Yearly</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Pioneer</td>
            <td style="text-align: right; padding-right: 1rem;">$4.97</td>
            <td style="text-align: right; padding-right: 1rem;">$45</td>
            <td>Early supporter, beta tester, helped shape the platform</td>
        </tr>
        <tr>
            <td>Early Adopter</td>
            <td style="text-align: right; padding-right: 1rem;">$11.97</td>
            <td style="text-align: right; padding-right: 1rem;">$115</td>
            <td>Joined at v1 launch or early statewide subscribers</td>
        </tr>
        <tr>
            <td>Patron</td>
            <td style="text-align: right; padding-right: 1rem;">$26.99</td>
            <td style="text-align: right; padding-right: 1rem;">$250</td>
            <td>Reliable tool user valuing web-based library power</td>
        </tr>
        <tr>
            <td>Sponsor</td>
            <td style="text-align: right; padding-right: 1rem;">$34.99</td>
            <td style="text-align: right; padding-right: 1rem;">$300</td>
            <td>Supports community and influences product direction</td>
        </tr>
        <tr>
            <td>Enterprise (School)</td>
            <td>–</td>
            <td style="text-align: right; padding-right: 1rem;">$399</td>
            <td>School-wide license through PO</td>
        </tr>
        <tr>
            <td>Enterprise (District)</td>
            <td>–</td>
            <td style="text-align: right; padding-right: 1rem;">$799</td>
            <td>District-wide license through PO</td>
        </tr>
        <tr>
            <td>Teacher</td>
            <td style="text-align: right; padding-right: 1rem;">$3.99</td>
            <td style="text-align: right; padding-right: 1rem;">$45</td>
            <td>Basic use without obligation to contribute programs</td>
        </tr>
        </tbody>
    </table>

    <p><strong>Community Contribution:</strong> All plans (except Teacher) include a commitment to add at least 2
        programs/year to help grow the searchable, insightful database for choral directors nationwide.</p>
</section>

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

    // const slide = document.getElementById('carouselSlide');
    // let index = 0;
    //
    // function showSlide(i) {
    //     const width = slide.clientWidth / 3;
    //     slide.style.transform = `translateX(-${i * width}px)`;
    // }
    //
    // function prevSlide() {
    //     index = (index - 1 + 3) % 3;
    //     showSlide(index);
    // }
    //
    // function nextSlide() {
    //     index = (index + 1) % 3;
    //     showSlide(index);
    // }
</script>


</body>
</html>
