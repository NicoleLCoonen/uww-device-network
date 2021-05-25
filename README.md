# uww-device-network
An interactive map of library data ports and equipment. For data visualization and management. 

This is a tool I am developing for the University of Wisconsin - Whitewater to easily keep track of what equipment we have
and where it is, as well as the status of our data jacks. Prior to the development of this project, all of that information
was compiled in PDF files, which I have included in order to show how far this project came. If you look at the PDFs, you 
will see that they contain a lot of unrelated information on things like seating and stack layout, resulting in visual clutter.
In addition, status information is rendered using red/green coloring, and model information is conveyed with font. This makes
for an unituitive and inacessible map, and the format makes it more tedious to keep updated. 

When the COVID-19 crisis hit and the library needed to respace its computers, the maps that I initially started working off of
were 11x17 printouts with updates made in pencil, which were not transferred to the PDFs, so even by the time I started working
with them they were out of date. This is why I am comfortable including them as a reference in this project.

Because this program was developed for a very specific use-case and would require a high degree of modification for implementation
in other settings, the primary purpose of its presence on GitHub is to show my approach to interactive mapping. It also serves as
an example of my coding style and language-learning capacity. I am a self-taught developer, and began learning PHP in June, 2020,
and CSS and JavaScript/JQuery in August of that year.

In order to develop this project, I needed to build a database from scratch. I could not have made the progress I did without the
help of student library staff members, who took on the responsibilities of data verification and entry. It would be unfair not to
give them their due credit for their work, which they performed admirably and to the highest degree of accuracy.

Clark Heideman
Louisa Latimer
Eriana Thomas
Maddi Burclaw
Sofia Maglio
Anna Zickus

I also recieved invaluable guidance from my colleague, Branden McCready.

The code itself, I feel, is somewhat unremarkable. PHP organizes data from the database into a multi-dimensional array, which is
converted to JSON and echoed in a data-caption. JS reads the JSON and puts the data into spans and tables, and JQuery provides
a means by which to interface with that data to keep it from becoming cluttered and unreadable. The CSS helps keep things tidy,
and PHP provides editing capabilites at the end.

