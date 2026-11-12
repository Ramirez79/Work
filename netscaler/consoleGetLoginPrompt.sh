# Identify the line starting with 'default' and search for the pattern that corresponds to the prompt
grep  "^default"  /etc/gettytab | sed  's/default::.*\\r\\n\(.*\)\ :sp.*/\1/'