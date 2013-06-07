---
layout: page
title: ComicsCalendar
---

# {{ page.title }}

<div class="grid-container">
	<div class="grid-100 main-description">
		<p>ComicsCalendar is a calendar interface for new comic books issues retrieved from <a href="http://www.comicvine.com">ComicVine</a>.</p>
		<p>This website allows you to follow some comic books series (or volumes), and see when the next issue will be published.</p>
	</div>
</div>

<div class="grid-container light-box">
	<div class="grid-33 tablet-grid-50 justified large-padding">
		<h2>Self-hosted</h2>
		<p>ComicsCalendar is meant to run on your own server. You only need a *AMP stack (Apache, MySql, PHP).</p>
		<p><a href="{{ site.baseurl }}/documentation.html">See the installation process</a></p>
	</div>
	<div class="grid-33 tablet-grid-50 justified large-padding">
		<h2>Open-source</h2>
		<p>Comics Calendar is released under an <a href="http://opensource.org/licenses/MIT">MIT License</a>, which means that you can use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of this application. The only restriction attached to it is that you have to include the original license in your copy.</p>
		<p>All the comic books information is retrieved from, and is a property of <a href="http://www.comincvine.com">ComicVine</a>.</p>
		<p><a href="{{ site.baseurl }}/download.html">Download the sources</a></p>
	</div>
	<div class="grid-33 tablet-grid-50 justified large-padding">
		<h2>Multi-user</h2>
		<p>ComicsCalendar is meant to be used as a multi-user application.</p>
	</div>
</div>
<div class="grid-container">
	<div class="grid-50 tablet-grid-50">
		<h3>Latest posts</h3>
		<ul class="home-list">
			{% for post in site.posts limit: 5 %}
				<li>
					<a href="{{ site.baseurl }}{{ post.url }}">{{ post.title }}</a>
					<small><i class="icon-time"> </i>{{ post.date | date:"%Y-%m-%d" }}</small>
				</li>
			{% endfor %}
		</ul>
	</div>
	<div class="grid-50 tablet-grid-50">
		<h3>Links</h3>
		<ul class="home-list">
			<li><a href="https://github.com/RemyG/ComicsCalendar">ComicsCalendar on Github</a></li>
			<li><a href="http://www.comincvine.com">ComicVine</a></li>
		</ul>
	</div>
</div>