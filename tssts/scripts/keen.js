(function() {
    var client = new Keen({
        projectId: "54a6d237672e6c1a4d2b952a",
        writeKey: "c00fb41768eb29321066ba2c0c12847021f11931d49dd54e111f324825bb0886d" +
                  "57a94f81f36ec190b37f892899dfb32e846bdc26e2e74b3e78dd1dbdc08fa6438" +
                  "daf51f4da212db6ac614e3d4c2b4f9f55b6eda8728341141f80c2e0ef22ce1112" +
                  "036e7816c0113721c22b46fa364db",
        readKey: "2e71377a91455ad4971a470412d87828e8518c8f76c54cc9d04b004f225a38a78" +
                 "3620b741d77d04f7ace741ae00f3c5e2da74ef4d2c1620af5377f2aa9257264ed" +
                 "5f137821c6272d0c984d7509137df0a69c5bb0ad729057b2d913ea25156b95466" +
                 "83dde41a364c9550afdd858cc2b4b",
        protocol: "https",
        host: "api.keen.io/3.0",
        requestType: "jsonp"
    });

    var parsed = queryString.parse(location.search);

    var funnel = {
        member: parsed.member ? parsed.member : "None",
        funnel: "Funnel Step 1",
        referrer: document.referrer,
        keen: {
            timestamp: new Date().toISOString()
        }
    };

    client.addEvent("funnels", funnel);
} ());
