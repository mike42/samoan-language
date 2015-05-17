# This compiles & minifies the stylesheets

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

clean:
	rm -f $(TARGETS)

