<?php
/*
 *
 *     Set multiple URLs for redirect and choose one at random
 *
 *     Created by: Paul Cushing    
 *   
 */

if (!empty($_GET)) {
    $destinations = array_keys($_GET);
    $rand = array_rand($destinations, 1);
    $final_destination = str_replace("_", ".", $destinations[$rand]);
    header('Location: ' . $final_destination);

    // No destinations specified - present creator form
} else { ?>

    <html>

    <head>
        <title>Easy Split Testing</title>

        <link rel="stylesheet" type="text/css" href="split.css">
        <script type="text/javascript">
            var baseURL = "https://abcl.ink?";




            var lnkId = 1; //start with 2 link fields

            function addLinkField() {
                lnkId++;
                var p = document.getElementById("links");
                var newField = document.createElement("INPUT");
                newField.setAttribute("type", "text");
                newField.setAttribute("id", "lnk" + lnkId);
                p.appendChild(newField);

            }

            /*      To Do:
             *      Check for empty fields and warn
             *      Check if http or https is there and add it if not
             */
            function createLink() {
                var destinations = "";
                var sep = "&";
                var p = document.getElementById("links");
                var allLinks = p.children;
                for (var i = 0; i < allLinks.length; i++) {
                    var eachId = "lnk" + i;
                    var linkValue = document.getElementById(eachId).value;
                    if (linkValue) {
                        destinations += encode(linkValue) + sep;
                    }
                }

                // Do output
                destinations = destinations.slice(0, -1);

                var finalLink = baseURL + destinations;

                var out = document.getElementById("displayLnk");
                out.value = finalLink;
                var outBlock = document.getElementById("displayOutput");
                outBlock.classList.add("is-showing");
                var createButton = document.getElementById("createBtn");
                createButton.value = "Update Link";

            }

            function encode(lnk) {
                return encodeURIComponent(lnk).replace(/'/g, "%27").replace(/"/g, "%22");
            }

            // Copy text link to clipboard
            function copyLink() {
                /* Get the text field */
                var copyText = document.getElementById("displayLnk");

                /* Select the text field */
                copyText.select();

                /* Copy the text inside the text field */
                document.execCommand("copy");
            }
        </script>
    </head>

    <body>

        <div class="modal-wrap">

            <div class="modal-header">
                <h1>Create A/B/C Test Link</h1>
            </div>
            <div class="modal-bodies">
                <div class="modal-body is-showing">
                    <form onsubmit="return false;">


                        <div id="links">
                            <input type="text" id="lnk0">
                            <input type="text" id="lnk1">
                        </div>
                        <div class="buttons">
                            <input type="button" id="addFieldBtn" onclick="addLinkField()" value="+">
                            <br><br><br>
                            <input type="button" id="createBtn" onclick="createLink()" value="Create Test Link">
                        </div>
                        <ul>
                            <li>Input as many landing page links as you have variants to split test. Click the "+" button to add additional destinations. (include https:// or http://)</li>
                            <li>The resulting link will forward the user to one of the URLs you've provided at random.</li>
                            <li>Click "COPY LINK" to copy the link to your clipboard. You can use it as is, or you can use a link shortener to beautify it and make it easier to share.</li>
                        </ul>

                    </form>
                    <div id="displayOutput">
                        <lable>Your Test Link:</lable>
                        <input type="text" id="displayLnk">
                        <input type="button" onclick="copyLink()" value="Copy Link">
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>

<?php
} ?>