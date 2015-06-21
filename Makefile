# This compiles & minifies the stylesheets
.PHONY: coverage
THEME=vendor/zbench
STYLE=public/style

TARGETS=$(STYLE)/style.css $(STYLE)/style.min.css $(STYLE)/images/icons.gif $(STYLE)/images/search-input-bg.gif

default: $(TARGETS)

$(STYLE)/style.css: $(THEME)/style.css $(STYLE)/custom.css
	cat $^ > $@

$(STYLE)/style.min.css: $(STYLE)/style.css
	yui-compressor $< > $@

$(STYLE)/images/%: $(THEME)/images/%
	cp $< $@

coverage:
	php vendor/bin/phpunit --coverage-html coverage/ tests/unit tests/integration

clean:
	rm -f $(TARGETS)
	rm --preserve-root -Rf coverage/

