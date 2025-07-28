<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheDirectorsRoom Library Module</title>
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


<section id="library">
    <h2>Library Sample</h2>
    <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_library.png') }}"
         alt="Sample Library Screenshot">
</section>

<section id="medley">
    <h2>Medley Input Sample</h2>
    <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_medley.png') }}"
         alt="Medley Input Screenshot">
</section>

<section id="pullsheet">
    <h2>Pull Sheet Sample</h2>
    <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_pullSheet.png') }}"
         alt="Pull Sheet Screenshot">
</section>

<section id="program">
    <h2>Program Sample</h2>
    <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url('library/images/sample_program.png') }}"
         alt="Program Screenshot">
</section>

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

</body>
</html>
