# OpenPhoda-SeV
Open Source web application for Phoda-SeV: a psychometric scale for Chronic Low Back Pain.

The University of Leuven releases a proprietary version of the Phoda-SeV for MS Windows computers. I thought it may have been useful to create a version of the software which (provided that a browser and internet connection is available) is capable to:

* run on any possible machine at any time 
* add data to a single, fast and reliable relational database

## What is OpenPhoda-SeV?

OpenPhoda-SeV is a webapp for delivery of the electronic version of the PHODA (Photograph Series of Daily Activities), a diagnostic tool using which uses photographs to determine the threat value of different physical activities and movements. Exactly like Phoda-SeV this tool offers a quick and simple way for therapists to create a fear hierarchy for patients that may be influenced by some degree of fear of movement. 

### Live versions

A free to use version of OpenPhoda can be found here: [OpenPhoda](https://openphoda.net/)

### Recent updates

The dashboard, drag-and-drop test board, and account pages recently got a UI facelift: tabbed navigation, a sortable results table, a tablet-friendly layout, and a handful of security/reliability fixes under the hood. Comments and feature requests are welcome - feel free to open an issue!

### Screenshots

![Title page](https://i.postimg.cc/Gh9kNLrg/2019-05-09-123856-1895x935-scrot.png "Title page")

![Experiemnt login/register](https://i.postimg.cc/FFTyDdtJ/2019-05-09-123921-1912x935-scrot.png "Experiemnt login/register")

![oPhoda interface](https://i.postimg.cc/15VKVV9C/2019-05-03-185434-1856x920-scrot.png "oPhoda interface")


## Minimum Requirement

Any webserver running:
* Apache 2.4.0 (or NGNIX)
* PHP 7.0.0+
* MySQL 5.7+ or mariaDB 10.3+


## References

### Phoda

The original PHODA was developed by Maastricht University together with Zuyd University. Reference: 

>Kugler, K., Wijn, J., Geilen, M., de Jong, J., & Vlaeyen, J. W. S. (1999). The Photograph series of Daily Activities (PHODA). CD-rom version 1.0.

### Phoda-SeV

Phoda-SeV was developed by Maastricht University. If Phoda-SeV is used for research purposes resulting in a publication or presentation, we request you to use the following reference:

> Leeuw, M., Goossens, M. E. J. B., van Breukelen, G. J. P., Boersma, K., & Vlaeyen, J. W. S. (2007). Measuring perceived harmfulness of physical activities in patients with chronic low back pain: the Photograph Series of Daily Activities - Short electronic Version. Journal of Pain, 8, 840-849.


