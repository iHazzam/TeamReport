<script type="text/html" class="project" id="template-project">
    <div class="col-md-7">
        <canvas id="chart"></canvas>
    </div>
    <aside class="col-md-5 info">
        <h1><a href="/#projects/<%= name %>"><%= name %></a></h1>
        <span class="id"><a href="/#projects/<%= id %>">#<%= id %></a></span>
        <p><%= company %></p>
        <table class="tasklists">
            <thead>
            <tr>
                <th></th>
                <th>Used</th>
                <th>Budget</th>
                <th>%</th>
            </tr>
            </thead>
            <tbody>
            <% _.forEach(tasklists, function(tasklist) { %>
              <%
              var tasklistused = Math.round(tasklist.used * 100) / 100;
              var tasklistbudget = Math.round(tasklist.budget * 100) / 100;
              var tasklistpercent = Math.round( (tasklistused / tasklistbudget) *100);
              %>
            <tr class="<%= (tasklistpercent > 100) ? 'over' : '' %>">
                <td class="key"><%= tasklist.name %></td>
                <td><%= tasklistused %> <span class="unit">hrs</span></td>
                <td><%= tasklistbudget %> <span class="unit">hrs</span></td>
                <td><%= ((tasklist.budget !== 0) ?  tasklistpercent : "N/A" ) %> <span class="unit">%</span></td>
            </tr>
            <% }); %>
            </tbody>
            <tfoot>
            <tr class="totals">
                <td class="key">Totals</td>
                <td><%= Math.round(used * 100) / 100 %> <span class="unit">hrs</span></td>
                <td><%= Math.round(budget * 100) / 100 %> <span class="unit">hrs</span></td>
                <td><%= Math.round(( (Math.round(used * 100) / 100)/(Math.round(budget * 100) / 100) )* 100)     %>  <span class="unit">%</span></td>
            </tr>
            </tfoot>
        </table>
    </aside>
</script>
