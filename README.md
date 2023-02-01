# Dashboard
It is simple info panel for display base info about your projects on your pc.

## How to install
You can install Dashboard like any site on PHP, so for installing need only Apache server with PHP. Database don't need. 

## Hotkey map
- `ctrl + shift + f` For set focus on search field
- `ctrl + q` For close search results or close project description or close something else
- `ctrl + i` For watching project colors
- `ctrl + enter` Open selected project in new tab
- `enter` For open description of selected project
And you can navigations on projects with used keyboard arrows.

## dashboard.json file (legacy name project.json)

	{
		"name": "Project name",
		"ver": "1.0",
		"authors": [
			{
				"name": "John Doe",
				"url": "https://john.doe.com",
				"email": "john.doe@mail.com"
			},
			{
				"name": "Mr. Anderson",
				"url": "https://mr.anderson.com",
				"email": "mr.anderson@mail.com"
			}
		],
		"links": {
			"release_url": "https://myproject.com",
			"docs": "https://github.com/john-doe/project/blob/master/README.md"
		},
		"repository": {
			"type": "git",
			"url": "https://github.com/john-doe/project"
		},
		"tags": ["tag1", "tag2", "tag3"],
		"status": "open",
		"type": "web||console||app||docs||other",
		"project_color": "#f60",
		"favicon": null,
		"main_lang": "php",
		"description": "This is description of project"
	}

If some project based in side from folders with projects, you can create empty folder with file dashboard.json and write inside `{ "path_to_project": "path/to/other/folder" }`

#### Console app for easy create dashboard.json file
You can use console script `php dashboard/init-project.php` for easy create dashboard.json

## How to use search field
- You can text tags with split comma for filtered projects by tags. Example `laravel, vue, open source`
- Write name of project for searching project name
- ~~You can text `open` or `close` for filtered project list by `status`~~