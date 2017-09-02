# Job Plugin

The plugin provides four components for building the vacancy list on page, category on page, vacancy details on page and category list for the sidebar, form for submit resume on page.

## Available languages

* en - English

## Implementing front-end pages

The plugin provides several components for building the vacancy list page (archive), category page, vacancy details page and category list for the sidebar.

### Vacancy list page

Use the `jobVacancies` component to display a list of latest job vacancies on a page. The component has the following properties:

* **pageNumber** - this value is used to determine what page the user is on, it should be a routing parameter for the default markup. The default value is **{{ :page }}** to obtain the value from the route parameter `:page`.
* **categoryFilter** - a category slug to filter the vacancies by. If left blank, all vacancies are displayed.
* **vacanciesPerPage** - how many vacancies to display on a single page (the pagination is supported automatically). The default value is 10.
* **noVacanciesMessage** - message to display in the empty vacancy list.
* **sortOrder** - the column name and direction used for the sort order of the vacancies. The default value is **published_at desc**.
* **categoryPage** - path to the category page. The default value is **job/category** - it matches the pages/job/category.htm file in the theme directory. This property is used in the default component partial for creating links to the job categories.
* **vacancyPage** - path to the vacancy details page. The default value is **job/vacancy** - it matches the pages/job/vacancy.htm file in the theme directory. This property is used in the default component partial for creating links to the job vacancies.

The jobVacancies component injects the following variables to the page where it's used:

* **vacancies** - a list of job vacancies loaded from the database.
* **vacancyPage** - contains the value of the `vacancyPage` component's property.
* **category** - the job category object loaded from the database. If the category is not found, the variable value is **null**.
* **categoryPage** - contains the value of the `categoryPage` component's property.
* **noVacanciesMessage** - contains the value of the `noVacanciesMessage` component's property.

The component supports pagination and reads the current page index from the `:page` URL parameter. The next example shows the basic component usage on the job home page:

    title = "Job"
    url = "/job/:page?"

    [jobVacancies]
    vacanciesPerPage = "5"
    ==
    {% component 'jobVacancies' %}

The next example shows the basic component usage with the category filter:

    title = "Job Category"
    url = "/job/category/:slug/:page?"

    [jobVacancies]
    categoryFilter = "{{ :slug }}"
    ==
    function onEnd()
    {
        // Optional - set the page title to the category name
        if ($this->category)
            $this->page->title = $this->category->name;
    }
    ==
    {% if not category %}
        <h2>Category not found</h2>
    {% else %}
        <h2>{{ category.name }}</h2>

        {% component 'jobVacancies' %}
    {% endif %}

The vacancy list and the pagination are coded in the default component partial `plugins/jetminds/job/components/vacancies/default.htm`. If the default markup is not suitable for your website, feel free to copy it from the default partial and replace the `{% component %}` call in the example above with the partial contents.

### Vacancy page

Use the `jobVacancy` component to display a job vacancy on a page. The component has the following properties:

* **slug** - the value used for looking up the vacancy by its slug. The default value is **{{ :slug }}** to obtain the value from the route parameter `:slug`.
* **categoryPage** - path to the category page. The default value is **job/category** - it matches the pages/job/category.htm file in the theme directory. This property is used in the default component partial for creating links to the job categories.

The component injects the following variables to the page where it's used:

* **vacancy** - the job vacancy object loaded from the database. If the vacancy is not found, the variable value is **null**.

The next example shows the basic component usage on the job page:

    title = "Job Vacancy"
    url = "/job/vacancy/:slug"

    [jobVacancy]
    ==
    <?php
    function onEnd()
    {
        // Optional - set the page title to the vacancy title
        if (isset($this->vacancy))
            $this->page->title = $this->vacancy->title;
    }
    ?>
    ==
    {% if vacancy %}
        <h2>{{ vacancy.title }}</h2>

        {% component 'jobVacancy' %}
    {% else %}
        <h2>Post not found</h2>
    {% endif %}

The vacancy details is coded in the default component partial `plugins/jetminds/job/components/vacancy/default.htm`.

### Category list

Use the `jobCategories` component to display a list of job vacancy categories with links. The component has the following properties:

* **slug** - the value used for looking up the current category by its slug. The default value is **{{ :slug }}** to obtain the value from the route parameter `:slug`.
* **displayEmpty** - determines if empty categories should be displayed. The default value is false.
* **categoryPage** - path to the category page. The default value is **job/category** - it matches the pages/job/category.htm file in the theme directory. This property is used in the default component partial for creating links to the job categories.

The component injects the following variables to the page where it's used:

* **categoryPage** - contains the value of the `categoryPage` component's property. 
* **categories** - a list of job categories loaded from the database.
* **currentCategorySlug** - slug of the current category. This property is used for marking the current category in the category list.

The component can be used on any page. The next example shows the basic component usage on the job home page:

    title = "Job"
    url = "/job/:page?"

    [jobCategories]
    ==
    ...
    <div class="sidebar">
        {% component 'jobCategories' %}
    </div>
    ...

The category list is coded in the default component partial `plugins/jetminds/job/components/categories/default.htm`.

### Submit Resume

Use the `jobResume` component to display a resume form of job on any page.

The component can be used on any page. The next example shows the basic component usage on the resume page:

    title = "Job"
    url = "job/resume"

    [jobResume]
    ==
    {% component 'jobResume' %}
    
The resume form is coded in the default component partial `plugins/jetminds/job/components/submitresume/default.htm`.

## Additional support
* additional features on request
* install service on request